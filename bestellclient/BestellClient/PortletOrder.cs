using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletOrder : BestellClient.Portlet
    {
        public PortletOrder()
        {
            InitializeComponent();
        }

        DTO.Recipe current = new DTO.Recipe();

        private void PortletOrder_Load(object sender, EventArgs e)
        {
            groupBoxErrorOrder.Visible = false;
            textBoxErrorOrder.Clear();
            textBoxWishes.Clear();
            buttonAbbrechen.Visible = true;
            buttonBestellen.Visible = true;
        }

        public void FocusCocktail(DTO.Recipe r)
        {
            current = r;
            LabelOrderedCocktailName.Text = current.Name;
            buttonAbbrechen.Visible = true;
            buttonBestellen.Visible = true;
        }

        private void buttonBestellen_MouseClick(object sender, MouseEventArgs e)
        {
            if (c.submitOrder(current, textBoxWishes.Text))
            {
                textBoxWishes.Clear();
                //MessageBox.Show("Bestellung erfolgreich übermittelt");

                /*
                groupBoxErrorOrder.ForeColor = System.Drawing.Color.Green;
                groupBoxErrorOrder.Text = "Bestellung erfolgreich";
                textBoxErrorOrder.ForeColor = System.Drawing.Color.Green;
                textBoxErrorOrder.Text = "Bestellung erfolgreich übermittelt!";
                groupBoxErrorOrder.Visible = true;
                 */
                buttonBestellen.Visible = false;
                buttonAbbrechen.Visible = false;
            }
            else
            {
                //MessageBox.Show("Es ist ein Fehler bei der Bestellung aufgetreten. Bitte setzen sie sich mit dem Gastgeber in Verbindung");

                groupBoxErrorOrder.ForeColor = System.Drawing.Color.Red;
                textBoxErrorOrder.ForeColor = System.Drawing.Color.Red;
                
                groupBoxErrorOrder.Text = "Fehler bei der Bestellung";
                textBoxErrorOrder.Text = "Es ist ein Fehler bei der Bestellung aufgetreten. Bitte setzen sie sich mit dem Gastgeber in Verbindung";
                groupBoxErrorOrder.Visible = true;
            }

        }

        private void buttonAbbrechen_MouseClick(object sender, MouseEventArgs e)
        {
            c.abortOrder();
        }

        private void buttonAbbrechen_Click(object sender, EventArgs e)
        {
            groupBoxErrorOrder.Visible = false;
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void buttonBestellen_Click(object sender, EventArgs e)
        {
            groupBoxErrorOrder.Visible = false;
        }

        private void textBoxWishes_TextChanged(object sender, EventArgs e)
        {
            c.setLastActivity();
        }

        public void clearWish()
        {
            textBoxWishes.Text = "";
        }


    }
}

