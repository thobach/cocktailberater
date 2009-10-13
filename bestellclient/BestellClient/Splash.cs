using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class Splash : Form
    {

        Control control;

        public Splash(Control c)
        {
            InitializeComponent();
            control = c;
        }

        private void StartupForm_Load(object sender, EventArgs e)
        {
            control.init();
               
        }
    }
}