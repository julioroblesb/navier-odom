Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")
strPath = fso.GetParentFolderName(WScript.ScriptFullName)
WshShell.CurrentDirectory = strPath

' Rutas absolutas
phpDir = fso.GetAbsolutePathName(strPath & "\..\php")
phpExe = phpDir & "\php.exe"
phpIni = phpDir & "\php.ini"
serverPhp = fso.GetAbsolutePathName(strPath & "\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php")

' 1. Ejecutar launcher.php y capturar el puerto
'    Usamos comillas dobles escapadas para manejar espacios en las rutas
launchCmd = Chr(34) & phpExe & Chr(34) & " -c " & Chr(34) & phpIni & Chr(34) & " " & Chr(34) & strPath & "\launcher.php" & Chr(34)
Set exec = WshShell.Exec(launchCmd)
output = exec.StdOut.ReadAll()

' Extraer el puerto de la salida
port = ""
If InStr(output, "RUNNING:") > 0 Then
    port = Split(output, ":")(1)
    port = Trim(port)
    ' Abrir navegador directamente porque ya esta corriendo
    WshShell.Run "cmd /c start http://127.0.0.1:" & port, 0, False
ElseIf InStr(output, "START:") > 0 Then
    port = Split(output, ":")(1)
    port = Replace(port, vbCr, "")
    port = Replace(port, vbLf, "")
    port = Trim(port)
    
    ' 2. Matar cualquier instancia anterior del servidor NAVIER
    Set objWMIService = GetObject("winmgmts:\\.\root\cimv2")
    Set colProcesses = objWMIService.ExecQuery("Select * from Win32_Process Where Name = 'php.exe'")
    For Each objProcess in colProcesses
        If InStr(1, objProcess.CommandLine, "NAVIER", 1) > 0 Then
            objProcess.Terminate()
        End If
    Next
    
    WScript.Sleep 1000
    
    ' Comando PHP con -c para forzar la carga del php.ini correcto
    cmdCommand = Chr(34) & phpExe & Chr(34) & " -c " & Chr(34) & phpIni & Chr(34) & " -S 127.0.0.1:" & port & " -t " & Chr(34) & strPath & "\public" & Chr(34) & " " & Chr(34) & serverPhp & Chr(34)
    
    ' 3. Lanzar el servidor de manera invisible usando un archivo .bat temporal
    ' 3. Lanzar el servidor de manera 100% invisible y desacoplada usando WMI
    Set objWMIService = GetObject("winmgmts:\\.\root\cimv2")
    Set objStartup = objWMIService.Get("Win32_ProcessStartup")
    Set objConfig = objStartup.SpawnInstance_
    objConfig.ShowWindow = 0 ' Hidden
    
    Set objProcessStarter = GetObject("winmgmts:root\cimv2:Win32_Process")
    objProcessStarter.Create cmdCommand, strPath & "\public", objConfig, intProcessID
    
    ' 4. Esperar a que el servidor responda (hasta 15 segundos)
    If WaitForServer(port) Then
        WshShell.Run "cmd /c start http://127.0.0.1:" & port, 0, False
    Else
        WScript.Echo "ERROR CRITICO: No se pudo iniciar el servidor PHP despues de multiples intentos." & vbCrLf & "Asegurese de que su antivirus no este bloqueando PHP o que el puerto " & port & " no este en uso."
    End If
Else
    WScript.Echo "Error al intentar iniciar el servidor." & vbCrLf & "Salida: " & output
End If

' Funcion para comprobar si el servidor ya esta levantado y funcionando correctamente
Function WaitForServer(portNum)
    Dim http, i, success
    Set http = CreateObject("MSXML2.ServerXMLHTTP")
    success = False
    For i = 1 To 15
        On Error Resume Next
        http.Open "GET", "http://127.0.0.1:" & portNum & "/up", False
        http.setTimeouts 2000, 2000, 5000, 5000
        http.Send
        If Err.Number = 0 Then
            If http.Status = 200 Then
                success = True
                Exit For
            End If
        End If
        On Error GoTo 0
        WScript.Sleep 1000
    Next
    WaitForServer = success
End Function
