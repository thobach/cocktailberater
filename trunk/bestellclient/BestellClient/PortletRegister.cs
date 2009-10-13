using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletRegister : BestellClient.Portlet
    {
        public PortletRegister()
        {
            InitializeComponent();
        }

        private void maskedTextBox1_MaskInputRejected(object sender, MaskInputRejectedEventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (validate())
            {
                if (c.register(textBox1.Text, textBox2.Text, textBox3.Text, maskedTextBox1.Text, textBox4.Text))
                {
                    // Erfolgreich registriert, jetzt alles löschen

                }
                else
                {
                    //Fehler anzeigen
                }
            }
        }

        private bool validate()
        {
            pictureBoxErrorBirthday.Visible = false;
            pictureBoxErrorEmail.Visible = false;
            pictureBoxErrorFirstname.Visible = false;
            pictureBoxErrorLogLastName.Visible = false;
            pictureBoxErrorPW.Visible = false;
            bool valid = true;
            textBoxError.Text = "";
            if (String.IsNullOrEmpty(textBox1.Text))
            {
                textBoxError.Text += "Bitte einen Vorname eingeben" + Environment.NewLine;
                pictureBoxErrorFirstname.Visible = true;
                valid = false;
            }
            if (String.IsNullOrEmpty(textBox2.Text))
            {
                textBoxError.Text += "Bitte einen Nachname eingeben" + Environment.NewLine;
                pictureBoxErrorLogLastName.Visible = true;
                valid = false;
            }
            if (String.IsNullOrEmpty(textBox3.Text))
            {
                textBoxError.Text += "Bitte eine Email Adresse eingeben" + Environment.NewLine;
                pictureBoxErrorEmail.Visible = true;
                valid = false;
            }
            if (String.IsNullOrEmpty(textBox4.Text))
            {
                textBoxError.Text += "Bitte ein Password eingeben" + Environment.NewLine;
                pictureBoxErrorPW.Visible = true;
                valid = false;
            }

            if (valid)
            {
                groupBox1.Visible = false;
              
            } else {
                groupBox1.Visible = true;
            }

            return valid;

        }

 
    }
}
