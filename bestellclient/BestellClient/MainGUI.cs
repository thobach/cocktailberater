using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class MainGUI : Form
    {
        DTO.User user;
        Control control;

       

        public MainGUI(Control c)
        {
            control = c;
            InitializeComponent();

        }

        private void MainGUI_Load(object sender, EventArgs e)
        {
            control.init();
            ausrichtung();
        }

        private void MainGUI_Click(object sender, EventArgs e)
        {
            //randomized sliden bei Klick ins Fenster
            //Random r = new Random();
            //sliderContainer1.slide(r.Next(4)+1);
        }

        public PortletDetails PortletDetails()
        {
            return (PortletDetails)sliderContainer1.Slider().Portlet("Details");
        }
        public PortletSearch PortletSearch()
        {
            return (PortletSearch)sliderContainer1.Slider().Portlet("Search");
        }
        public PortletLogIn PortletLogin()
        {
            return (PortletLogIn)sliderContainer1.Slider().Portlet("Login");
        }
        public PortletOrder PortletOrder()
        {
            return (PortletOrder)sliderContainer1.Slider().Portlet("Order");
        }

        public PortletLogo PortletLogo()
        {
            return (PortletLogo)sliderContainer1.Slider().Portlet("Logo");
        }

        public PortletRegister portletRegister()
        {
            return (PortletRegister)sliderContainer1.Slider().Portlet("Register");
        }

        public bool slide(int n)
        {
            return sliderContainer1.slide(n);
        }

        private void sliderContainer1_Load(object sender, EventArgs e)
        {

        }

        private void buttonLogOut_MouseClick(object sender, MouseEventArgs e)
        {
            control.logout();
        }

        public void showLogout(){
            buttonLogOut.Visible = true;
        }

        public void hideLogout(){
            buttonLogOut.Visible = false;
        }

        public void UserLoggedIn(DTO.User current)
        {
            user = current;
            timer1.Enabled = true;
            UserRefresh();
        }

        public void UserBillSum(decimal sum)
        {
            UserRefresh();
        }

        public void UserRefresh()
        {
            if(user != null){
                 labelUser.Text = user.Username;
                 labelUser.Visible = true;
                 labelBill.Text = "aktueller Rechnungsbetrag: " + user.BillSum+ " €";
                 labelBill.Visible = true;
             }
        }

        public void ClearUserData()
        {
            user = null;
            labelUser.Text = "";
            labelBill.Text = "";
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            UserRefresh();            
        }

        public void showMessage(string title, string msg, Color col)
        {
            groupBoxMessage.ForeColor = col;
            groupBoxMessage.Text = title;
            textBoxMessage.ForeColor = col;
            textBoxMessage.Text = msg;
            groupBoxMessage.Visible = true;
            textBoxMessage.Visible = true;
            timerMesssage.Enabled = true;
        }

        public void clearMessage(){
            groupBoxMessage.Visible = false;
        }

        private void timerMesssage_Tick(object sender, EventArgs e)
        {
            clearMessage();
            timerMesssage.Enabled = false;
            
        }

        private void timerAutomaticLogoutcheck_Tick(object sender, EventArgs e)
        {
            bool erg = control.DoLogout;//Getter erledigt arbeit
        }

        private void ausrichtung()
        {
            int rahmenOben = pictureBoxLogoTop.Height + pictureBoxLogoTop.Top;
            int rahmenUnten = 15;
            sliderContainer1.Top = (int)((this.Height - rahmenOben - rahmenUnten) / 2) + rahmenOben - (sliderContainer1.Height / 2);
        }

        private void MainGUI_ResizeEnd(object sender, EventArgs e)
        {
            ausrichtung();
        }

        private void MainGUI_SizeChanged(object sender, EventArgs e)
        {
            ausrichtung();
        }

        private void MainGUI_Resize(object sender, EventArgs e)
        {
            ausrichtung();
        }

        public void showLogo()
        {
            sliderContainer1.Slider().showLogo();
        }
        public void showRegister()
        {
            sliderContainer1.Slider().showRegister();
        }

    }
}