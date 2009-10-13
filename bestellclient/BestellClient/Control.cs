using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Forms;
using System.Web;
using DTO;
using DataConnection;
using System.Threading;

namespace BestellClient
{
    public class Control
    {
        private bool doLogout = false;
        public bool DoLogout
        {
            get
            {
                if (doLogout)
                {
                    doLogout = false;
                    logout();
                    return true;
                }
                return false;
            }
            
        }
        private bool applicationRuns = true;
        private Cocktails cocktails;
        private MainGUI mainGui;
        private User currentUser=new User();
        private CocktailFetcher dcGetCocktails;
        private List<DTO.Party> parties;
        private DTO.Party party;
        private BackgroundUpdater bgUpdater;
        private DateTime lastActivity = DateTime.Now;
        private Thread automaticLogouter;



        public Cocktails Cocktails
        {
            get { return cocktails; }
            set { cocktails = value; }
        }

        public Control()
        {
            
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            DataConnection.DataConnection.startTests();
            mainGui = new MainGUI(this);
            cocktails = Cocktails.Instance; 
            bgUpdater = new BackgroundUpdater(this,cocktails);
            automaticLogouter = new Thread(automaticLogout);
            automaticLogouter.Start();
            Application.Run(mainGui);
            DataConnection.DataConnection.stopTests();
            applicationRuns = false;
        }

        public void init()
        {
            mainGui.PortletDetails().C = this;
            mainGui.PortletLogin().C = this;
            mainGui.PortletOrder().C = this;
            mainGui.PortletSearch().C = this;
            mainGui.PortletLogo().C = this;
            mainGui.portletRegister().C = this;
            dcGetCocktails = new CocktailFetcher(cocktails);
            parties = new DataConnectionPartys().getPartys();
            mainGui.PortletLogo().showParties(parties);
            
        }

        public void partyChosen(DTO.Party p) {
            setLastActivity();
            party = p;
            mainGui.PortletLogin().startLogin();
            mainGui.hideLogout();
        }

        public bool checkUserDaten(String username, String password)
        {
            setLastActivity();

            User u_temp = new DataConnection.DataConnectionLogin().login(username, MD5HashString(password));
            
            if (u_temp!=null)
            {
                slide(3);
                
                mainGui.PortletSearch().loadList();
                currentUser = u_temp;
                updateInvoice();
                mainGui.showLogout();
                mainGui.UserLoggedIn(currentUser);
            }
            else
            {
                return false;
            }
            return true;
        }

        public void newRegistrationForm()
        {
            mainGui.showRegister();
        }

        public bool register(String firstname, String lastname, String email, String geburtstag, String pw)
        {
            String[] g = geburtstag.Split('.');
            geburtstag= g[2] + "-" +g[1] + "-" + g[0];
            pw = MD5HashString(pw);
            DataConnectionRegister dcr = new DataConnectionRegister();
            if (dcr.register(firstname, lastname, email, geburtstag, pw))
            {//erfolgreich registriert
                mainGui.showLogo();
                mainGui.showMessage("Registrierung erfolgreich", "Du hast dich erfolgreich registriert", System.Drawing.Color.Black);
                return true;
            }
            else
            {//fehlerhaft registriert
                return false;
            }

        }

        public List<DTO.Recipe> searchResult(String pattern)
        {
            setLastActivity();
            if (String.IsNullOrEmpty(pattern))
            {
                return cocktails.getAllCocktails();
            }
            else
            {
                return cocktails.getCocktailList(pattern);
            }
            
        }

        public void cocktailSelected(DTO.Recipe cocktail)
        {
            setLastActivity();
            mainGui.PortletDetails().fill_details(cocktail);
            mainGui.PortletOrder().clearWish();
        }

        public void newCocktailOrder(DTO.Recipe cocktail)
        {
            setLastActivity();
            mainGui.PortletOrder().FocusCocktail(cocktail);
            slide(4);
        }

        public bool submitOrder(DTO.Recipe cocktail, String message)
        {
            setLastActivity();
            //Versuchen zu Bestellen
            DataConnectionNewOrder order = new DataConnectionNewOrder();
            if (order.order(currentUser, cocktail,party, System.Web.HttpUtility.UrlEncode(message)))
            {
                slide(3);
                String title = "Bestellung erfolgreich";
                String msg = "Bestellung erfolgreich übermittelt!";
                updateInvoice();
                mainGui.showMessage(title, msg, System.Drawing.Color.Green);
                return true;
            }
            //else
            return false;
        }

        public void abortOrder()
        {
            setLastActivity();
            slide(3);
        }

        public void logout()
        {
            setLastActivity();
            if (slide(1))
            {
                currentUser = new User();
                mainGui.hideLogout();
                mainGui.ClearUserData();
            }
        }

        private bool slide(int n)
        {
            setLastActivity();
            return mainGui.slide(n);
            
        }


        public void refreshPartyList()
        {
            parties = new DataConnectionPartys().getPartys();
            mainGui.PortletLogo().showParties(parties);
            setLastActivity();
        }

        public void setLastActivity() 
        {
            lastActivity = DateTime.Now;
        }

        public void automaticLogout()
        {
            while (applicationRuns)
            {
                Thread.Sleep(2000);
                if (lastActivity<(DateTime.Now.AddMinutes(-2)))
                {
                    doLogout=true;
                }
            }
        }

        private string MD5HashString(string pw)
        {
            System.Security.Cryptography.MD5CryptoServiceProvider provider = new System.Security.Cryptography.MD5CryptoServiceProvider();
            byte[] bytes = System.Text.Encoding.UTF8.GetBytes(pw);
            bytes = provider.ComputeHash(bytes);
            System.Text.StringBuilder stringBuilder = new System.Text.StringBuilder();
            foreach (byte b in bytes)
            {
                stringBuilder.Append(b.ToString("x2").ToLower());
            }
            string password = stringBuilder.ToString();
            return password;
        }

        private void updateInvoice()
        {
            DataConnectionInvoice dci = new DataConnectionInvoice();

            currentUser.BillSum = dci.invoiceValue(currentUser.UserId, party.Id);
            mainGui.UserRefresh();
        }
    }
}
