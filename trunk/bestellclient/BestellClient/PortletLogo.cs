using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletLogo : BestellClient.Portlet
    {
        List<DTO.Party> partyList;

        public PortletLogo()
        {
            InitializeComponent();
        }

        private void PortletLogo_Load(object sender, EventArgs e)
        {
            this.Refresh();
        }

        private void PortletLogo_Paint(object sender, PaintEventArgs e)
        {
            
        
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        public void showParties(List<DTO.Party> parties)
        {
            partyList = parties;
            listBoxLocation.Items.Clear();
            for (int i = 0; i < partyList.Count; i++)
            {
                //comboBoxLocation.Items.Add(partyList[i].Name);
                listBoxLocation.Items.Add(partyList[i].Name);
            }
           

        }

        private void buttonLocation_MouseClick(object sender, MouseEventArgs e)
        {

            if (listBoxLocation.SelectedIndex >= 0 &&  listBoxLocation.SelectedIndex<partyList.Count)
            {

                c.partyChosen(partyList[listBoxLocation.SelectedIndex]);
                listBoxLocation.Visible = false;
                buttonLocation.Visible = false;
                labelLocation.Visible = false;
                groupBoxLocation.Visible = false;
                pictureBoxLogo.Visible = true;
                LogoResize();
            }
            else
            {
                //MessageBox.Show("Bitte eine Location auswählen!");
                labelErrorLocation.Visible = true;
                groupBoxErrorLocation.Visible = true;
                pictureBoxErrorLocation.Visible = true;
            }

            /*
             * if(comboBoxLocation.SelectedIndex >= 0)
            {

                c.partyChosen(partyList[comboBoxLocation.SelectedIndex]);
                comboBoxLocation.Visible = false;
                buttonLocation.Visible = false;
                labelLocation.Visible = false;
                pictureBoxLogo.Visible = true;
                LogoResize();
            }else{
                //MessageBox.Show("Bitte eine Location auswählen!");
                labelErrorLocation.Visible = true;
                groupBoxErrorLocation.Visible = true;
                pictureBoxErrorLocation.Visible = true;
            }
             */

        }

        private void LogoResize()
        {
            int width = pictureBoxLogo.Width;
            int height = pictureBoxLogo.Height;
            int width_faktor = (width-10)/150;
            int height_faktor = (height-20)/150;

            pictureBoxLogo.Width = 10;
            pictureBoxLogo.Height = 20;

            while (pictureBoxLogo.Width < width)
            {

                pictureBoxLogo.Width += width_faktor;
                pictureBoxLogo.Height += height_faktor;
                pictureBoxLogo.Refresh();
            }

        }

        private void listBoxLocation_SelectedIndexChanged(object sender, EventArgs e)
        {
            pictureBoxErrorLocation.Visible = false;
            groupBoxErrorLocation.Visible = false;
        }

        private void toolStripMenuItem1_Click(object sender, EventArgs e)
        {
            c.refreshPartyList();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            c.newRegistrationForm();
        }

    }
}

