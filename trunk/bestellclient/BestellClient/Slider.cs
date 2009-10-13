
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class Slider : UserControl
    {
        public Slider()
        {
            InitializeComponent();
        }

        public Portlet Portlet(String name)
        {
            if (name == "Details") return portletDetails1;
            if (name == "Search") return portletSearch1;
            if (name == "Login") return portletLogIn1;
            if (name == "Order") return portletOrder1;
            if (name == "Logo") return portletLogo1;
            if (name == "Register") return portletRegister1;

            throw new Exception("Funktion Portlet im Slider fehlerhaft aufgerufen");
        }

        public void showRegister()
        {
            portletLogo1.Visible = false;
            portletRegister1.Visible = true;
        }

        public void showLogo()
        {
            portletLogo1.Visible = true;
            portletRegister1.Visible = false;
        }

        private void portletLogIn1_Load(object sender, EventArgs e)
        {

        }

    }
}
