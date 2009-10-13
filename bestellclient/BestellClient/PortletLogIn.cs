using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletLogIn : BestellClient.Portlet
    {
        public PortletLogIn()
        {
            InitializeComponent();
        }

        private void PortletLogIn_Load(object sender, EventArgs e)
        {

        }

        public void startLogin()
        {
            label1.Visible = false;
            label2.Visible = false;
            textBoxName.Visible = false;
            textBoxName.Enabled = false;
            textBoxPassword.Visible = false;
            textBoxPassword.Enabled = false;
            buttonLogin.Visible = false;
            
            LogoResize();

            label1.Visible = true;
            label2.Visible = true;
            textBoxName.Visible = true;
            textBoxName.Enabled = true;
            textBoxPassword.Visible = true;
            textBoxPassword.Enabled = true;
            
            buttonLogin.Visible = true;

            Validate();
            Update();

        }

        private void startlogin()
        {
            textBoxErrorLogIn.Clear();

            if (textBoxName.Text != "" && textBoxPassword.Text != "")
            {
                if (!c.checkUserDaten(textBoxName.Text, textBoxPassword.Text))
                {
                    textBoxErrorLogIn.Text = "Log-In fehlgeschlagen! Bitte Anmeldedaten überprüfen!\n";
                    groupBoxErrorLogIn.Visible = true;
                    //LabelErrorLogIn.Text = "Fehlerhafter Log-In";
                }
                else
                {
                    textBoxName.Clear();
                    textBoxPassword.Clear();
                }
            }
            else
            {
                if (textBoxName.Text == "")
                {
                    pictureBoxErrorLogInName.Visible = true;
                    textBoxErrorLogIn.Text += "Bitte einen gültigen Namen eingeben!" + Environment.NewLine;
                    groupBoxErrorLogIn.Visible = true;
                }
                if (textBoxPassword.Text == "")
                {
                    pictureBoxErrorLogInPwd.Visible = true;
                    textBoxErrorLogIn.Text += "Bitte ein gültiges Passwort eingeben!" + Environment.NewLine;
                    groupBoxErrorLogIn.Visible = true;
                }
            }
        }

        private void buttonLogin_Click(object sender, EventArgs e)
        {
            startlogin();

            //MainGUI.
        }

        private void LogoResize()
        {
            int width = pictureBoxLogoLogIn.Width;
            int height = pictureBoxLogoLogIn.Height;
            int width_faktor = width / 150;
            int height_faktor = height / 150;

            while (pictureBoxLogoLogIn.Width > 0)
            {

                pictureBoxLogoLogIn.Width -= width_faktor;
                pictureBoxLogoLogIn.Height -= height_faktor;
                pictureBoxLogoLogIn.Refresh();
                pictureBoxLogoLogIn.Update(); 
            }

            pictureBoxLogoLogIn.Visible = false;
            pictureBoxLogoLogIn.Enabled = false;
        }

        private void textBoxName_TextChanged(object sender, EventArgs e)
        {
            groupBoxErrorLogIn.Visible = false;
            pictureBoxErrorLogInPwd.Visible = false;
            pictureBoxErrorLogInName.Visible = false;
        }

     

        private void textBoxPassword_MaskInputRejected(object sender, MaskInputRejectedEventArgs e)
        {
            groupBoxErrorLogIn.Visible = false;
            pictureBoxErrorLogInPwd.Visible = false;
            pictureBoxErrorLogInName.Visible = false;
        }

        private void textBoxPassword_TextChanged(object sender, EventArgs e)
        {
        
            groupBoxErrorLogIn.Visible = false;
            pictureBoxErrorLogInPwd.Visible = false;
            pictureBoxErrorLogInName.Visible = false;
        
        }

        private void textBoxPassword_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == (char)Keys.Enter)
            {
                e.Handled = true;
                startlogin();
            }
        }

    }
}

