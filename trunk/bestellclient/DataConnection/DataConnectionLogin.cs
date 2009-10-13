using System;
using System.Collections.Generic;
using System.Text;
using DTO;
using System.Xml;

namespace DataConnection
{
    public class DataConnectionLogin : DataConnection
    {
        public User login(String username, String passwordhash)
        {
            User u;
            String request = "member/login/email/"+username+"/password-md5/"+passwordhash;
            XmlDocument doc = HTTPRequestToXML(request);
            XmlNode resp = doc.DocumentElement;
            if (resp == null)
            {
                Console.Error.WriteLine("HTTPError / Leere XML Datei bei member/login");
            }
            else if (resp.Attributes["status"].Value.ToLower() != "ok")
            {
                return null;
            }
            else
            {
                u=new User();
                //u.BillSum
                u.IsLogedIn = true;
                u.UserId = resp.ChildNodes[0].Attributes["id"].Value;
                u.Firstname = resp.ChildNodes[0].Attributes["firstname"].Value;
                u.Lastname = resp.ChildNodes[0].Attributes["lastname"].Value;
                u.Username = u.Firstname + " " + u.Lastname;
                u.HashCode = resp.ChildNodes[0].Attributes["hashCode"].Value;
                return u;

            }
            return null;
        }

    }
}
