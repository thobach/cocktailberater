using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Drawing;
using System.Data;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class SliderContainer : UserControl
    {
        int currentposition = 1;
        int currentpositionpx = 0;
        int scrolldirection = 1;
        Boolean scrolling = false;
        int scrolldistance = 0;
        double speed = 0;
        const double scrolltime = 1000;
        DateTime scrollbegin;
        int newposition;

        public SliderContainer()
        {
            InitializeComponent();
        }

        private void slider1_Load(object sender, EventArgs e)
        {

        }

        /**
         * beginnt das Sliden zu einer Zielposition n
         */
        public bool slide(int n)
        {            
            if (n != currentposition && !scrolling)
            {
                newposition = n;
                //Console.WriteLine("next position:" +n);
                scrolling = true;
                currentpositionpx = slider1.Left;
                //Console.WriteLine("Currentpositionpx: "+currentpositionpx);
                if (newposition < currentposition)
                {
                    scrolldirection = 1;
                    scrolldistance = currentposition - newposition;
                }
                else
                {
                    scrolldirection = -1;
                    scrolldistance = newposition - currentposition;
                }
                speed = scrolldistance * 500 / scrolltime;
               // Console.WriteLine("Speed: " + speed);
                scrollbegin = DateTime.Now;
                //Console.WriteLine("Scrollbegin: " + scrollbegin);
                timer1.Enabled = true;
                return true;
        }
        return false;
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            //double elapsedTime = (DateTime_to_ms(DateTime.Now) - scrollbegin);
            double elapsedTime = (DateTime.Now.Subtract(scrollbegin)).TotalMilliseconds;
            
            //Console.WriteLine("elapsedTime: " + elapsedTime); 
            slider1.Left = (int)((double)currentpositionpx + ((speed * elapsedTime)*(double)scrolldirection));
           // Console.WriteLine("new Slider1.left: " + slider1.Left);
            if (elapsedTime >= scrolltime)
            {
                timer1.Enabled = false;
                scrolling = false;
                slider1.Left = -1*((newposition-1) * 500);
                currentposition = newposition;
                //Console.WriteLine("New Currentposition: " + currentposition);
            }

        }

        /*
        private double DateTime_to_ms(DateTime t)
        {
            
            return ((((t.Hour * 60) + t.Minute * 60) + t.Second * 1000) + t.Millisecond);

        }
        */
        public Slider Slider()
        {
            return slider1;
        }

    }
}
