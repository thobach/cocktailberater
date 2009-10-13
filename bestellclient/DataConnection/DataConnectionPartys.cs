using System;
using System.Collections.Generic;
using System.Text;
using System.Xml;

namespace DataConnection
{
    public class DataConnectionPartys:DataConnection
    {


        public List<DTO.Party> getPartys()
        {
            List<DTO.Party> l = new List<DTO.Party>();
            XmlDocument doc = HTTPRequestToXML("party/get-all");
            XmlNode resp = doc.DocumentElement;
            if (resp==null)
            {
                Console.Error.WriteLine("HTTPError / Leere XML Datei bei party/getall");
            }
            else if (resp.Attributes["status"].Value.ToLower() != "ok")
            {
                Console.Error.WriteLine("XmlError bei party/getall");
            }
            else
            {
                XmlNode allPartyNode = resp.FirstChild;
                Console.WriteLine(allPartyNode.Attributes["count"].Value);
                XmlNodeList allPartysNodeList = allPartyNode.ChildNodes;
                for (int i = 0; i < allPartysNodeList.Count; i++)
                {
                    parseParty(allPartysNodeList[i],l);
                }

            }
            return l;

        }

        private void parseParty(XmlNode node, List<DTO.Party> l)
        {
            DTO.Party p=new DTO.Party();
            p.Id = node.Attributes["id"].Value;
            p.Name = node.Attributes["name"].Value;
            l.Add(p);
        }
    }
}
