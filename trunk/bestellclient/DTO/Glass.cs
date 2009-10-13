using System;
using System.Collections.Generic;
using System.Text;

namespace DTO
{
    public class Glass
    {
        String id;

        public String Id
        {
            get { return id; }
            set { id = value; }
        }

        String name;

        public String Name
        {
            get { return name; }
            set { name = value; }
        }

        Photo photo=new Photo();

        public Photo Photo
        {
            get { return photo; }
            set { photo = value; }
        }

         
    }
}
