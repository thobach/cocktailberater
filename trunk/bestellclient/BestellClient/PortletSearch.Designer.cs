namespace BestellClient
{
    partial class PortletSearch
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(PortletSearch));
            System.Windows.Forms.ListViewItem listViewItem4 = new System.Windows.Forms.ListViewItem("c1", 0);
            System.Windows.Forms.ListViewItem listViewItem5 = new System.Windows.Forms.ListViewItem("c2", 0);
            System.Windows.Forms.ListViewItem listViewItem6 = new System.Windows.Forms.ListViewItem("c3", 1);
            this.searchField = new System.Windows.Forms.TextBox();
            this.imageList1 = new System.Windows.Forms.ImageList(this.components);
            this.LabelSearch = new System.Windows.Forms.Label();
            this.ListViewCocktail = new System.Windows.Forms.ListView();
            this.SuspendLayout();
            // 
            // searchField
            // 
            this.searchField.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.searchField.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.searchField.Font = new System.Drawing.Font("Verdana", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.searchField.ForeColor = System.Drawing.Color.White;
            this.searchField.Location = new System.Drawing.Point(16, 9);
            this.searchField.Margin = new System.Windows.Forms.Padding(3, 3, 3, 0);
            this.searchField.Name = "searchField";
            this.searchField.Size = new System.Drawing.Size(165, 27);
            this.searchField.TabIndex = 0;
            this.searchField.TextChanged += new System.EventHandler(this.searchField_TextChanged);
            // 
            // imageList1
            // 
            this.imageList1.ImageStream = ((System.Windows.Forms.ImageListStreamer)(resources.GetObject("imageList1.ImageStream")));
            this.imageList1.TransparentColor = System.Drawing.Color.Transparent;
            this.imageList1.Images.SetKeyName(0, "Non-alcohol.png");
            this.imageList1.Images.SetKeyName(1, "Bitmap1.bmp");
            // 
            // LabelSearch
            // 
            this.LabelSearch.Font = new System.Drawing.Font("Verdana", 15.75F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.LabelSearch.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.LabelSearch.Location = new System.Drawing.Point(181, 9);
            this.LabelSearch.Name = "LabelSearch";
            this.LabelSearch.Size = new System.Drawing.Size(100, 24);
            this.LabelSearch.TabIndex = 2;
            this.LabelSearch.Text = "Suche";
            this.LabelSearch.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // ListViewCocktail
            // 
            this.ListViewCocktail.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.ListViewCocktail.Cursor = System.Windows.Forms.Cursors.Hand;
            this.ListViewCocktail.Font = new System.Drawing.Font("Verdana", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.ListViewCocktail.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.ListViewCocktail.FullRowSelect = true;
            this.ListViewCocktail.Items.AddRange(new System.Windows.Forms.ListViewItem[] {
            listViewItem4,
            listViewItem5,
            listViewItem6});
            this.ListViewCocktail.LargeImageList = this.imageList1;
            this.ListViewCocktail.Location = new System.Drawing.Point(16, 41);
            this.ListViewCocktail.MultiSelect = false;
            this.ListViewCocktail.Name = "ListViewCocktail";
            this.ListViewCocktail.Size = new System.Drawing.Size(265, 424);
            this.ListViewCocktail.SmallImageList = this.imageList1;
            this.ListViewCocktail.TabIndex = 1;
            this.ListViewCocktail.TileSize = new System.Drawing.Size(244, 25);
            this.ListViewCocktail.UseCompatibleStateImageBehavior = false;
            this.ListViewCocktail.View = System.Windows.Forms.View.Tile;
            this.ListViewCocktail.SelectedIndexChanged += new System.EventHandler(this.ListViewCocktail_SelectedIndexChanged);
            // 
            // PortletSearch
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.Controls.Add(this.LabelSearch);
            this.Controls.Add(this.ListViewCocktail);
            this.Controls.Add(this.searchField);
            this.Name = "PortletSearch";
            this.Load += new System.EventHandler(this.PortletSearch_Load);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.TextBox searchField;
        private System.Windows.Forms.ListView ListViewCocktail;
        private System.Windows.Forms.ImageList imageList1;
        private System.Windows.Forms.Label LabelSearch;

    }
}
