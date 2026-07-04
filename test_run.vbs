Set WshShell = CreateObject("WScript.Shell")
strPath = "C:\Users\Julio\OneDrive\Desktop\NAVIER\Contadores equipos Ricoh\Dejalo Aqui\server"
phpExe = "C:\Users\Julio\OneDrive\Desktop\NAVIER\Contadores equipos Ricoh\Dejalo Aqui\php\php.exe"
phpIni = "C:\Users\Julio\OneDrive\Desktop\NAVIER\Contadores equipos Ricoh\Dejalo Aqui\php\php.ini"
serverPhp = strPath & "\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php"
port = "8005"

cmdCommand = Chr(34) & phpExe & Chr(34) & " -c " & Chr(34) & phpIni & Chr(34) & " -S 127.0.0.1:" & port & " -t " & Chr(34) & strPath & "\public" & Chr(34) & " " & Chr(34) & serverPhp & Chr(34)

Set fso = CreateObject("Scripting.FileSystemObject")
Set batFile = fso.CreateTextFile(strPath & "\run.bat", True)
batFile.WriteLine cmdCommand
batFile.Close

WshShell.CurrentDirectory = strPath & "\public"
WshShell.Run Chr(34) & strPath & "\run.bat" & Chr(34), 0, False
WScript.Echo "Ran bat file with: " & cmdCommand
