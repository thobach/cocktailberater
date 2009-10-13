using System;
using System.Collections.Generic;
using System.Text;
using DTO;

namespace BestellClient
{
    public class Cocktails
    {
        private System.Collections.Generic.List<DTO.Recipe> cocktailRecipesList;
        private static Cocktails instance;

        private Cocktails() {
            cocktailRecipesList = new List<Recipe>();
        }

        public static Cocktails Instance
        {
            get
            {
                if (instance == null)
                {
                    instance = new Cocktails();
                }
                return instance;
            }
        }


        public List<DTO.Recipe> getCocktailList(String pattern)
        {
            pattern = pattern.ToLower().Trim() ;

            String[] patterns = pattern.Split(new Char [] {' '});
            // leere Liste erzeugen
            List<DTO.Recipe> result = new List<Recipe>();

            // Alle Cocktails mit allen Attributen durchlaufen und ggf adden
            for (int i = 0; i < cocktailRecipesList.Count; i++)
            {
                bool[] isFound = new bool[patterns.Length];
                //Recipe Name

                for (int wordCount = 0; wordCount < patterns.Length; wordCount++)
                {
                    isFound[wordCount] = false;

                    if (cocktailRecipesList[i].Name.ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;

                    //Cocktail Name
                    if (cocktailRecipesList[i].Cocktail.Name.ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;

                    //Cocktail Decription
                    if (cocktailRecipesList[i].Descripton.ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;

                    //Components
                    for (int n = 0; n < cocktailRecipesList[i].Components.Count; n++)
                    {
                        if (cocktailRecipesList[i].Components[n].ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;
                    }

                    //Categories
                    for (int n = 0; n < cocktailRecipesList[i].Categories.Count; n++)
                    {
                        if (cocktailRecipesList[i].Categories[n].ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;
                    }

                    //tags
                    for (int n = 0; n < cocktailRecipesList[i].Tags.Count; n++)
                    {
                        if (cocktailRecipesList[i].Tags[n].ToLower().Contains(patterns[wordCount])) isFound[wordCount] = true;
                    }


                    // Weitere Suchen implementieren
                }

                bool alltrue = true;
                for (int wordCount = 0; wordCount < isFound.Length; wordCount++)
                {
                    if (!isFound[wordCount]) alltrue = false;
                }
                if (alltrue) result.Add(cocktailRecipesList[i]);
            }

            return result;
        }

        public List<DTO.Recipe> getAllCocktails()
        {
            return cocktailRecipesList;
        }

        public void newReciepsLoaded(List<DTO.Recipe> l)
        {
            if (l == null)
            {
                Console.Error.WriteLine("Cocktailliste konnte nicht geladen werden");
                cocktailRecipesList = new List<Recipe>();
            }
            else
            {
                cocktailRecipesList = l;
            }
        }
    }
}
