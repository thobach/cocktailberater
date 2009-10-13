using System;
using System.Collections.Generic;
using System.Text;

namespace DTO
{
    public class Recipe
    {
        Cocktail c=new Cocktail();
        public Cocktail Cocktail
        {
            get { return c; }
            set { c = value; }
        }
     
        String id="";
        public String Id
        {
            get { return id; }
            set { id = value; }
        }
       
        String name="";
        public String Name
        {
            get { return name; }
            set { name = value.Replace("\\", ""); }
        }
        
        String descripton="";
        public String Descripton
        {
            get { return descripton; }
            set { descripton = value.Replace("\\", ""); }
        }
        
        String alclevel="";
        public String Alclevel
        {
            get { return alclevel; }
            set { alclevel = value; }
        }
        
        String calories="";
        public String Calories
        {
            get { return calories; }
            set { calories = value; }
        }
        
        String volume="";
        public String Volume
        {
            get { return volume; }
            set { volume = value; }
        }

        Glass glas=new Glass();
        public Glass Glas
        {
            get { return glas; }
            set { glas = value; }
        }

        bool isAlcoholic=false;
        public bool IsAlcoholic
        {
            get { return isAlcoholic; }
            set { isAlcoholic = value; }
        }

        String price="";
        public String Price
        {
            get { return price; }
            set { price = value; }
        }

        int rating=0;
        //Between 0 and 5

        public int Rating
        {
            get { return rating; }
            set { rating = value; }
        }

        List<String> components=new List<string>();

        public List<String> Components
        {
            get { return components; }
            set { components = value; }
        }

        List<String> categories = new List<string>();

        public List<String> Categories
        {
            get { return categories; }
            set { categories = value; }
        }

        List<String> tags = new List<string>();

        public List<String> Tags
        {
            get { return tags; }
            set { tags = value; }
        }

        private List<Photo> photos = new List<Photo>();

        public List<Photo> Photos
        {
            get { return photos; }
            set { photos = value; }
        }



        public Recipe()
        {
            components = new List<string>();
            glas = new Glass();
        }
    }
}
