using System;
using System.Diagnostics;

class Program
{
    static void Main(string[] args)
    {
        // Verificar si se proporcionó la ruta al archivo BAT como argumento
        if (args.Length != 1)
        {
            Console.WriteLine("Usage: execBatWithLowPriority.exe <path_to_batch_file>");
            return;
        }

        // Obtener la ruta al archivo BAT desde los argumentos de la línea de comandos
        string batchFile = args[0];

        // Verificar si el archivo BAT existe
        if (!System.IO.File.Exists(batchFile))
        {
            Console.WriteLine("El archivo BAT especificado no existe.");
            return;
        }

        // Iniciar el proceso del archivo BAT
        ProcessStartInfo startInfo = new ProcessStartInfo();
        startInfo.FileName = batchFile;
        startInfo.UseShellExecute = false;
        startInfo.CreateNoWindow = true;
        startInfo.WindowStyle = ProcessWindowStyle.Hidden;
        startInfo.RedirectStandardOutput = true;
        startInfo.RedirectStandardError = true;

        using (Process process = new Process())
        {
            process.StartInfo = startInfo;
            process.Start();

            // Establecer la prioridad del proceso a Idle
            process.PriorityClass = ProcessPriorityClass.Idle;

            process.WaitForExit();
        }
    }
}
