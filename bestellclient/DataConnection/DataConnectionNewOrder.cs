using System;
using System.Collections.Generic;
using System.Text;
using System.Xml;

namespace DataConnection
{
    public class DataConnectionNewOrder:DataConnection
    {
        public DataConnectionNewOrder()
        {

        }

        public bool order(DTO.User u, DTO.Recipe r,DTO.Party p, String message)
        {
            //http://api-stage.cocktailberater.de/order/add/member/2/party/1/recipe/26/
            String query = "order/add/member/"+u.UserId+"/party/"+p.Id+"/recipe/"+r.Id+"/comment/"+message+"/hashcode/"+u.HashCode;
            XmlDocument doc = HTTPRequestToXML(query);
            XmlNode resp = doc.DocumentElement;
            if (resp==null)
            {
                Console.Error.WriteLine("HTTPError / Leere XML Datei bei neuer Bestellung");
                return false;
            }
            else if (resp.Attributes["status"].Value.ToLower() != "ok")
            {
                Console.Error.WriteLine("XmlError bei neuer Bestellung: "+query);
                Console.Error.WriteLine(resp.InnerXml);
                return false;
            }
            else
            {
                //Check ob wirklich true...
                if (resp.Attributes["status"].Value.ToLower() == "ok") return true;
            }
            return false;


        }
    }
}
