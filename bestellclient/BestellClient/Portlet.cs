using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class Portlet : UserControl
    {
        protected Control c;

        public Control C
        {
            
            set { c = value; }
        }


        public Portlet()
        {
            InitializeComponent();
        }

        private void Portlet_Load(object sender, EventArgs e)
        {
           // this.Refresh();
        }

       private void Portlet_Paint(object sender, PaintEventArgs e)
        {
           /*
            Graphics grfx = e.Graphics;
           // grfx.Clear(System.Drawing.SystemColors.Control);

            Pen p = new Pen(Color.Red);
            p.Width = 5;
            e.Graphics.DrawLine(p, 0, 0, Width, 0);
            e.Graphics.DrawLine(p, 0, 0, 0, Height);
            e.Graphics.DrawLine(p, Width, Height, Width, 0);
            e.Graphics.DrawLine(p, Width, Height, 0, Height);
           */
        }
        
    }
}
