using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace BestellClient
{
    class ErrorLogger : TextWriter
    {
        private TextWriter writer;
        public override Encoding Encoding
        {
            get
            {
                return (Encoding.Unicode);
            }
        }

        public ErrorLogger(String fn, bool append)
        {
            writer = new StreamWriter(fn, append);

        }

        public override void WriteLine(String s) {
            writer.Write(DateTime.Now.ToString() + " : ");
            writer.WriteLine(s);
            writer.Flush();
        }

        public override void WriteLine()
        {
            writer.WriteLine();
            writer.Flush();
        }

    }
}
