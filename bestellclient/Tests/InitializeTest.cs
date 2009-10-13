using System;
using System.Collections.Generic;
using System.Text;
using DataConnection;
using BestellClient;

namespace Tests
{
    using NUnit.Framework;
    using DTO;
    using BestellClient;

    [TestFixture]
    public class InitializeTest

    {
        [Test]
        public void initialTest()
        {
            DTO.Cocktail c = new Cocktail();
            Assert.IsNotNull(c);

            //BestellClient.Control bc = new Control();
            
            //Assert.IsNotNull(bc);
          


        }

        [Test]
        public void cocktailListUpdate()
        {
            DataConnectionGetCocktails dcgc = new DataConnectionGetCocktails();
            Assert.IsInstanceOfType(typeof(DataConnectionGetCocktails), dcgc);
            dcgc.run();
            
            Assert.IsInstanceOfType(typeof(List<Recipe>), dcgc.Recipes);
            List<Recipe> l = dcgc.Recipes;
            Assert.GreaterOrEqual(l.Count, 1);

            BestellClient.BackgroundUpdater bgu = new BestellClient.BackgroundUpdater();
            SortedList<String, String> sl1 = dcgc.getAllCocktailIDs();
            Assert.IsTrue(bgu.isIdentical(sl1,sl1));

            SortedList<String,String> sl2 = new SortedList<string,string>();

            Assert.IsFalse(bgu.isIdentical(sl1, sl2));

            sl2 = dcgc.getAllCocktailIDs();
            Assert.IsTrue(bgu.isIdentical(sl1, sl2));

            
            sl2.RemoveAt(0);
            sl2.Add("0", "0");

            Assert.IsFalse(bgu.isIdentical(sl1, sl2));
        }
    }
}
