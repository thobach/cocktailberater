using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;

namespace BestellClient
{
    public class BackgroundUpdater
    {
        Control control;
        Cocktails cocktails;
        Thread cocktailListeUpdater;
        Thread userbillUpdater;

        public BackgroundUpdater()
        {

        }

        public BackgroundUpdater(Control c1, Cocktails c2)
        {
            control = c1;
            cocktails = c2;
            cocktailListeUpdater = new Thread(updateCocktails);
            userbillUpdater = new Thread(updateBill);
            cocktailListeUpdater.Start();
          //  userbillUpdater.Start();

        }

        private void updateCocktails()
        {
            Thread.Sleep(10000);
            //Check ob Daten sich geändert haben, evtl. neu laden


            /*
            SortedList<String,String> newIDs = new DataConnection.DataConnectionGetCocktails().getAllCocktailIDs();
            bool identical = true;

            if (newIDs.Count != cocktails.getAllCocktails().Count)
            {
                identical = false;
            }
            else
            {
                // Sortierte Listen vergleichen
                

                for (int i = 0; i < oldIDs.Count; i++)
                {
                    if (!oldIDs.Values[i].Equals(newIDs.Values[i])) identical=false;
                }

            }
            */

            SortedList<String, String> oldIDs = new SortedList<string, string>();
            
                for (int i = 0; i < cocktails.getCocktailList("").Count;i++ )
                {
                    oldIDs.Add(cocktails.getCocktailList("")[i].Id, cocktails.getCocktailList("")[i].Id);
                }


            if (!isIdentical(new DataConnection.DataConnectionGetCocktails().getAllCocktailIDs(),oldIDs))
            {
                DataConnection.DataConnectionGetCocktails dc = new DataConnection.DataConnectionGetCocktails();
                dc.run();
                cocktails.newReciepsLoaded(dc.Recipes);
                Console.Error.WriteLine("Neue Cocktails geladen");
            }

        }

        public bool isIdentical(SortedList<String, String> s1, SortedList<String, String> s2)
        {
            bool identical = true;

            if (s1.Count != s2.Count)
            {
                identical = false;
            }
            else
            {
                // Sortierte Listen vergleichen


                for (int i = 0; i < s1.Count; i++)
                {
                    if (!s1.Values[i].Equals(s2.Values[i])) identical = false;
                }

            }
            return identical;
        }

        private void updateBill()
        {
            Thread.Sleep(5000);
            //Daten für die Bill neu laden
        }


    }
}
