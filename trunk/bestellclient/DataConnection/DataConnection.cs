using System;
using System.Collections.Generic;
using System.Text;
using System.Xml;
using System.Threading;
using System.Configuration;

namespace DataConnection
{
    public abstract class DataConnection
    {
        public static String serverURL;

        public static bool isServerReachable=false;
        protected static Thread t;
        protected static bool runTests = true;

        public DataConnection()
        {
            // SeverURL Festlegen
            serverURL = ConfigurationSettings.AppSettings["serverUrl"];
            if (!serverURL.EndsWith("/")) serverURL += "/";
        }

        protected static void testServerReachable()
        {
            DataConnectionPartys dcp = new DataConnectionPartys();
            while (runTests)
            {
                XmlDocument xmldoc = dcp.HTTPRequestToXML("test/test", true);
                if (xmldoc!=null){
                   XmlNode resp = xmldoc.DocumentElement; 
                   if (resp!=null) {
                       if (resp.Attributes["status"].Value.ToLower() == "ok") {
                           isServerReachable=true;
                       }
                       else {isServerReachable=false;}
                   }else {isServerReachable=false;}
               }else {isServerReachable=false;}
              
                Thread.Sleep(1000);
            }
        }

        public static void startTests()
        {
            t=new Thread(testServerReachable);
            t.Start();
        }

        protected XmlDocument HTTPRequestToXML(String url)
        {
            return HTTPRequestToXML(url, false);
        }
        protected XmlDocument HTTPRequestToXML(String url, bool test)
        {
            int i = 0;
            while (!DataConnection.isServerReachable && i < 10 && !test)
            {
                i++;
                Thread.Sleep(1000);
            }


            if (DataConnection.isServerReachable || test)
            {

                url = DataConnection.serverURL + url;

                XmlDocument result = new XmlDocument();
                try
                {

                    result.Load(url);

                }
                catch (Exception ex)
                {
                    Console.Error.WriteLine("Fehler beim laden der url:" + url);
                    Console.Error.WriteLine(ex.Message);
                    Console.Error.WriteLine(ex.StackTrace);
                    return new XmlDocument();
                }
                return result;
            }
            else
            {
                Console.Error.WriteLine("Trotz 10 Sec warten ist der Server noch nicht erreichbar beim call: " + url);
                return new XmlDocument();
            }
           
            
        }


        public static void stopTests()
        {
            runTests = false;
        }
    }
}
