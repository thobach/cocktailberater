using System;
using System.Collections.Generic;
using System.Text;
using System.Drawing;
using System.Net;
using System.Threading;
using System.Collections;


namespace DTO
{
    public class Photo
    {
        Thread t;
        static Dictionary<string, Bitmap> dict = new Dictionary<string, Bitmap>();
        public static Object lockvar = "";

        private String id = "";

        public String Id
        {
            get { return id; }
            set { id = value; }
        }
        private String name = "";

        public String Name
        {
            get { return name; }
            set { name = value; }
        }
        private String description = "";

        public String Description
        {
            get { return description; }
            set { description = value; }
        }
        private String url = "";

        public String Url
        {
            get { return url; }
            set { url = value; t.Start(); }
        }
        private bool imageLoaded = false;

        public bool ImageLoaded
        {
            get { return imageLoaded; }
        }

        private Bitmap image;

        public Bitmap Image
        {
            get { return image; }
        }

    

        private void loadImageThread()
        {
            lock (lockvar) {

                if (dict.ContainsKey(url) && dict[url] != null)
                {
                    image = dict[url];
                    //Console.Error.WriteLine("Foto wurde nicht geladen, da es schon geladen ist");
                }
                else
                {
                    load();
                }
            }
        }

        private void load()
        {
            if (!dict.ContainsKey(url))
            {
                dict.Add(url, null);
                
            }
            else Console.Error.WriteLine("Evl race condition bei den Photos");
            try
            {
                WebRequest request = WebRequest.Create(url);
                WebResponse response = request.GetResponse();
                System.IO.Stream responseStream =
                    response.GetResponseStream();
                image = new Bitmap(responseStream);

                dict[url] = image;
                imageLoaded = true;
            }
            catch (System.Net.WebException)
            {
                Console.Error.WriteLine("Error beim laden des Bildes: " + url);

            }
        }

       

        public Photo()
        {
            t = new Thread(loadImageThread);
        }
        

    }
}
