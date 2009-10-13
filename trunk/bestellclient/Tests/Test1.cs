using System;
using System.Collections.Generic;
using System.Text;

namespace Tests
{
    using NUnit.Framework;
    using DTO;

    [TestFixture]
    public class Test1

    {
        [Test]
        public void initialTest()
        {
            DTO.Cocktail c = new Cocktail();

            Assert.IsNotNull(c);
        }
    }
}
