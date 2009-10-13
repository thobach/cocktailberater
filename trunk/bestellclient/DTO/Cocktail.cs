using System;
using System.Collections.Generic;
using System.Text;

namespace DTO
{
    public class Cocktail
    {
        private String name="";

        public String Name
        {
            get { return name; }
            set { name = value.Replace("\\",""); }
        }

        String id="";

        public String Id
        {
            get { return id; }
            set { id = value; }
        }
    }
}
