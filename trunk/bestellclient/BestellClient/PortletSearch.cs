using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletSearch : BestellClient.Portlet
    {
        public PortletSearch()
        {
            InitializeComponent();
        }

        List<DTO.Recipe> results = new List<DTO.Recipe> ();

        private void PortletSearch_Load(object sender, EventArgs e)
        {
            
           
        }


        private void searchField_TextChanged(object sender, EventArgs e)
        {
            loadList();
        }

        public void loadList()
        {
            searchField.Focus();
            results = c.searchResult(searchField.Text);
            ListViewCocktail.Clear();
            if (results.Count > 0)
            {

                for (int i = 0; i < results.Count; i++)
                 {
                      int imgind = 0;
                      if(results[i].IsAlcoholic)
                      {
                          imgind = 1;
                      }
                      else
                      {
                          imgind = 0;
                      }
                      ListViewCocktail.Items.Add(results[i].Name, imgind);
                      
                 }
                 Random ra = new Random();
                 c.cocktailSelected(results[ra.Next(results.Count)]);

            }else{

                ListViewCocktail.Items.Add("Suche erfolglos!", 2);

            }
            
        }

        private void ListViewCocktail_SelectedIndexChanged(object sender, EventArgs e)
        {

            if (ListViewCocktail.SelectedItems.Count == 1)
            {
                int cocktailIndex = ListViewCocktail.SelectedItems[0].Index;
                if (results.Count > 0) 
                {
                    c.cocktailSelected(results[cocktailIndex]);
                }
                else ListViewCocktail.SelectedIndices.Clear();
            }
        }
    }
}

