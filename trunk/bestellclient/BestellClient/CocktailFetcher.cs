using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;

namespace BestellClient
{
    class CocktailFetcher
    {
        Cocktails cocktails;
        Thread t;
        DataConnection.DataConnectionGetCocktails gc;
        
        public CocktailFetcher(Cocktails c)
        {
            cocktails = c;
            gc = new DataConnection.DataConnectionGetCocktails();

            t = new Thread(run);
            t.Start();
            
        }

        public void checkForUpdates()
        {
            // Check

            //Neuladen
        }

        private void run()
        {
            gc.run();
            cocktails.newReciepsLoaded(gc.Recipes);

        }

    }
}
