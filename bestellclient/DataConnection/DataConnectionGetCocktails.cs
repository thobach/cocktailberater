using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;
using System.Xml;
using System.Collections;

namespace DataConnection
{
    public class DataConnectionGetCocktails : DataConnection
    {
        List<DTO.Recipe> recipes;

        public List<DTO.Recipe> Recipes
        {
            get { return recipes; }
           
        }

       

        private XmlNode getCocktailXML()
        {
            XmlDocument doc = HTTPRequestToXML("cocktail/get-all");
            

            XmlNode resp = doc.DocumentElement;
            if (resp==null)
            {
                Console.Error.WriteLine("HTTPError / Leere XML Datei bei getCocktails");
            }
            else if (resp.Attributes["status"].Value.ToLower() != "ok")
            {
                Console.Error.WriteLine("XmlError bei getCocktails");
            }
            else
            {
                return resp;
            }
            return null;

        }

        public SortedList<String,String> getAllCocktailIDs()
        {
            SortedList<String,String> l = new SortedList<String,String>();
            XmlNode resp = getCocktailXML();
            if (resp != null)
            {

                XmlNode allCocktailNode = resp.FirstChild;
                XmlNodeList allCocktailNodeList = allCocktailNode.ChildNodes;
                for (int i = 0; i < allCocktailNodeList.Count; i++)
                {
                    XmlNode recipe = allCocktailNodeList[i].FirstChild.FirstChild;

                    while (recipe != null)
                    {
                        l.Add(recipe.Attributes["id"].Value, recipe.Attributes["id"].Value);
                        recipe = recipe.NextSibling;
                    }
                    
                }
            }
            return l;
        }

        public void run()
        {
            XmlNode resp = getCocktailXML();
            if (resp!=null)
            {
                XmlNode allCocktailNode = resp.FirstChild;
                Console.WriteLine(allCocktailNode.Attributes["count"].Value);
                XmlNodeList allCocktailNodeList = allCocktailNode.ChildNodes;
                recipes = new List<DTO.Recipe>();
                for (int i = 0; i < allCocktailNodeList.Count; i++)
                {
                    parseCocktail(allCocktailNodeList[i], recipes);
                }
            }
        }

        private void parseCocktail(XmlNode node, List<DTO.Recipe> list)
        {
            DTO.Cocktail c = new DTO.Cocktail();
            c.Id = node.Attributes["id"].Value;
            c.Name = node.Attributes["name"].Value;

            XmlNode recipe = node.FirstChild.FirstChild;

            while (recipe!=null) {
                parseRecipe(recipe, list,c);
                recipe=recipe.NextSibling;
            }
            

        }

        private void parseRecipe(XmlNode node, List<DTO.Recipe> list,DTO.Cocktail c)
        {
            DTO.Recipe r = new DTO.Recipe();
            r.Cocktail = c;
            try
            {
                r.Alclevel = node.Attributes["alcoholLevel"].Value;
                r.Calories = node.Attributes["caloriesKcal"].Value;
                //r.Descripton = node.Attributes["decription"].Value;
                r.Id = node.Attributes["id"].Value;
                r.IsAlcoholic = (node.Attributes["isAlcoholic"].Value == "1");
                r.Name = node.Attributes["name"].Value;
                r.Volume = node.Attributes["volumeCl"].Value;
                r.Price = node.Attributes["price"].Value;
                r.Rating = Int32.Parse(node.Attributes["rating"].Value);
            }
            catch (Exception ex)
            {
                System.Console.Error.WriteLine("Fehler beim erzeugen eines Cocktails");
                r = null;
            }
            if (r!=null) {
                for (int i = 0; i < node.ChildNodes.Count; i++)
                {
                    if (node.ChildNodes[i].Name.ToLower() == "glass")
                    {
                        parseGlass(node.ChildNodes[i], r);
                    }
                    if (node.ChildNodes[i].Name.ToLower() == "components")
                    {
                        parseComponents(node.ChildNodes[i].ChildNodes, r);
                    }
                    if (node.ChildNodes[i].Name.ToLower() == "categories")
                    {
                        parseCategories(node.ChildNodes[i].ChildNodes, r);
                    }
                    if (node.ChildNodes[i].Name.ToLower() == "photos")
                    {
                        parsePhotos(node.ChildNodes[i].ChildNodes, r);
                    }
                    if (node.ChildNodes[i].Name.ToLower() == "tags")
                    {
                        parseTags(node.ChildNodes[i].ChildNodes, r);
                    }

                }
            }
            
            if (r!=null)  list.Add(r);
        }

        private void parseGlass(XmlNode glass, DTO.Recipe r)
        {
            r.Glas.Id = glass.Attributes["id"].Value;
            r.Glas.Name = glass.Attributes["name"].Value;
            r.Glas.Photo.Description = glass.FirstChild.Attributes["description"].Value;
            r.Glas.Photo.Id = glass.FirstChild.Attributes["id"].Value;
            r.Glas.Photo.Name = glass.FirstChild.Attributes["name"].Value;
            r.Glas.Photo.Url = glass.FirstChild.Attributes["url"].Value;
        }

        private void parseComponents(XmlNodeList components, DTO.Recipe r)
        {
            for (int i = 0; i < components.Count; i++)
            {
                r.Components.Add(components[i].Attributes["name"].Value);
            }
        }

        private void parseCategories(XmlNodeList categories, DTO.Recipe r)
        {
            for (int i = 0; i < categories.Count; i++)
            {
                r.Categories.Add(categories[i].Attributes["name"].Value);
            }
        }

        private void parsePhotos(XmlNodeList photos, DTO.Recipe r)
        {
            for (int i = 0; i < photos.Count; i++)
            {
                DTO.Photo p = new DTO.Photo();
                p.Description = photos[i].Attributes["description"].Value;
                p.Id = photos[i].Attributes["id"].Value;
                p.Name = photos[i].Attributes["name"].Value;
                p.Url = photos[i].Attributes["url"].Value;
                r.Photos.Add(p);
            }
        }

        private void parseTags(XmlNodeList tags, DTO.Recipe r)
        {
            for (int i = 0; i < tags.Count; i++)
            {
                r.Tags.Add(tags[i].Attributes["name"].Value);
            }
          
        }

    }
}
