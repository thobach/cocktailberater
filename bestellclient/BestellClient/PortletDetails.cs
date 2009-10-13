using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace BestellClient
{
    public partial class PortletDetails : BestellClient.Portlet
    {
        DTO.Recipe rec;
        List<DTO.Photo> photos;
        int currentpic = 0;

        public PortletDetails()
        {
            InitializeComponent();
        }


        private void updatePicIndex()
        {
            labelPhotoCount.Text = currentpic+1 +" / "+photos.Count;
        }

        

        public void fill_details(DTO.Recipe thisrec)
        {
            rec = thisrec;

            labelName.Text = rec.Name;
            labelAlc.Text = rec.Alclevel + " % Alc";
            labelKcal.Text = rec.Calories + " kCal";
            textBoxDescription.Text = rec.Descripton;
            textBoxGlas.Text = rec.Glas.Name;
            labelPrice.Text = rec.Price + " €";
            labelVolume.Text = rec.Volume + " cl";
            textBoxComponents.Text = "";

            rate(rec.Rating);
            

            for (int i = 0; i < rec.Components.Count; i++)
            {

                
                textBoxComponents.Text += rec.Components[i];
                if (i < rec.Components.Count - 1)
                {
                    textBoxComponents.Text += ", ";
                }


            }

            pictureBoxGlas.BackgroundImage = null;
            pictureBoxGlas.BackgroundImage = rec.Glas.Photo.Image;
            generate_pictures();
           
        }

        private void rate(int rate)
        {
            Console.WriteLine("Rating: " + rec.Rating);
            List<PictureBox> stars = new List<PictureBox>();

            stars.Add(Star1);
            stars.Add(Star2);
            stars.Add(Star3);
            stars.Add(Star4);
            stars.Add(Star5);

            for (int i = 0; i <= 4; i++)
            {
                if (i <= (rate-1) && rate > 0)
                {
                    stars[i].BackgroundImage = Resource1.starblack;
                    Console.WriteLine("Ändere Bil: " + i);
                }
                else
                {
                    stars[i].BackgroundImage = Resource1.Not_yet_rated_1;
                }
            }
        }

        private void generate_pictures()
        {
            photos = new List<DTO.Photo>();
            for (int i = 0; i < rec.Photos.Count; i++){

                if(rec.Photos[i].ImageLoaded && rec.Photos[i]!=null)
                {
                    photos.Add(rec.Photos[i]);
                }
            }

            

            if (photos.Count == 0)
            {
                currentpic = 0;
                pictureBoxCocktail.BackgroundImage = Resource1.noPicture;
                buttonLeft.Visible = false;
                buttonRight.Visible = false;
                labelPhotoCount.Visible = false;

            } 
            else if (photos.Count == 1)
            {
                currentpic = 0;
                pictureBoxCocktail.BackgroundImage = photos[currentpic].Image;
                buttonLeft.Visible = false;
                buttonRight.Visible = false;
                labelPhotoCount.Visible = false;
            }
            else if (photos.Count > 1)
            {
                currentpic = 0;
                pictureBoxCocktail.BackgroundImage = photos[currentpic].Image;
                buttonLeft.Visible = true;
                buttonRight.Visible = true;
                labelPhotoCount.Visible = true;
            }

            updatePicIndex();
        }


        private void PortletDetails_Load(object sender, EventArgs e)
        {

        }

        private void ButtonOrder_MouseClick(object sender, MouseEventArgs e)
        {
            if(rec != null)
            {
                c.newCocktailOrder(rec);
            }else{
                MessageBox.Show("Bitte zunächst einen Cocktail auswählen!");
            }

        }

        private void labelName_Click(object sender, EventArgs e)
        {

        }

        private void textBoxComponents_TextChanged(object sender, EventArgs e)
        {

        }

        private void buttonLeft_Click(object sender, EventArgs e)
        {

            if (currentpic == 0)
            {
                currentpic = photos.Count - 1;
            }
            else
            {
                currentpic--;
            }

            pictureBoxCocktail.BackgroundImage = photos[currentpic].Image;

            updatePicIndex();

            // changePicture(currentpic - 1);
            // Console.WriteLine("links geklickt");
        }

        private void buttonRight_Click(object sender, EventArgs e)
        {
            if (currentpic == (photos.Count - 1))
            {
                currentpic = 0;
            }
            else
            {
                currentpic++;
            }

            pictureBoxCocktail.BackgroundImage = photos[currentpic].Image;
            updatePicIndex();
            //Console.WriteLine("rechts geklickt");
        }



    }
}

