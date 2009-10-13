using System;
using System.Collections.Generic;
using System.Windows.Forms;
using System.IO;

namespace BestellClient
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            string folder = @"log";
            DirectoryInfo di= new DirectoryInfo(folder);
            if (!di.Exists) Directory.CreateDirectory(folder);

            string fn = @"log/error.log";
            ErrorLogger errStream = new ErrorLogger(fn, true);
            Console.SetError(errStream);
            Console.Error.WriteLine("Application startet");
            new Control();
            
        }
    }
}