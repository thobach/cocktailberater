namespace BestellClient
{
    partial class PortletOrder
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
            this.buttonAbbrechen = new System.Windows.Forms.Button();
            this.buttonBestellen = new System.Windows.Forms.Button();
            this.groupBoxWishes = new System.Windows.Forms.GroupBox();
            this.textBoxWishes = new System.Windows.Forms.TextBox();
            this.LabelOrderedCocktailName = new System.Windows.Forms.Label();
            this.groupBoxErrorOrder = new System.Windows.Forms.GroupBox();
            this.textBoxErrorOrder = new System.Windows.Forms.TextBox();
            this.groupBoxWishes.SuspendLayout();
            this.groupBoxErrorOrder.SuspendLayout();
            this.SuspendLayout();
            // 
            // buttonAbbrechen
            // 
            this.buttonAbbrechen.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.buttonAbbrechen.ForeColor = System.Drawing.SystemColors.ControlLightLight;
            this.buttonAbbrechen.Location = new System.Drawing.Point(282, 354);
            this.buttonAbbrechen.Name = "buttonAbbrechen";
            this.buttonAbbrechen.Size = new System.Drawing.Size(86, 30);
            this.buttonAbbrechen.TabIndex = 5;
            this.buttonAbbrechen.Text = "Abbrechen";
            this.buttonAbbrechen.UseVisualStyleBackColor = true;
            this.buttonAbbrechen.Visible = false;
            this.buttonAbbrechen.Click += new System.EventHandler(this.buttonAbbrechen_Click);
            this.buttonAbbrechen.MouseClick += new System.Windows.Forms.MouseEventHandler(this.buttonAbbrechen_MouseClick);
            // 
            // buttonBestellen
            // 
            this.buttonBestellen.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.buttonBestellen.ForeColor = System.Drawing.SystemColors.ControlLightLight;
            this.buttonBestellen.Location = new System.Drawing.Point(374, 354);
            this.buttonBestellen.Name = "buttonBestellen";
            this.buttonBestellen.Size = new System.Drawing.Size(91, 30);
            this.buttonBestellen.TabIndex = 4;
            this.buttonBestellen.Text = "Bestellen";
            this.buttonBestellen.UseVisualStyleBackColor = true;
            this.buttonBestellen.Visible = false;
            this.buttonBestellen.Click += new System.EventHandler(this.buttonBestellen_Click);
            this.buttonBestellen.MouseClick += new System.Windows.Forms.MouseEventHandler(this.buttonBestellen_MouseClick);
            // 
            // groupBoxWishes
            // 
            this.groupBoxWishes.BackColor = System.Drawing.Color.Transparent;
            this.groupBoxWishes.Controls.Add(this.textBoxWishes);
            this.groupBoxWishes.Font = new System.Drawing.Font("Verdana", 14.25F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))));
            this.groupBoxWishes.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.groupBoxWishes.Location = new System.Drawing.Point(17, 43);
            this.groupBoxWishes.Name = "groupBoxWishes";
            this.groupBoxWishes.Size = new System.Drawing.Size(454, 305);
            this.groupBoxWishes.TabIndex = 7;
            this.groupBoxWishes.TabStop = false;
            this.groupBoxWishes.Text = "Bemerkungen, Wünsche ...";
            // 
            // textBoxWishes
            // 
            this.textBoxWishes.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBoxWishes.Font = new System.Drawing.Font("Verdana", 11.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.textBoxWishes.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.textBoxWishes.Location = new System.Drawing.Point(6, 30);
            this.textBoxWishes.Multiline = true;
            this.textBoxWishes.Name = "textBoxWishes";
            this.textBoxWishes.Size = new System.Drawing.Size(442, 269);
            this.textBoxWishes.TabIndex = 0;
            this.textBoxWishes.TextChanged += new System.EventHandler(this.textBoxWishes_TextChanged);
            // 
            // LabelOrderedCocktailName
            // 
            this.LabelOrderedCocktailName.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.LabelOrderedCocktailName.BackColor = System.Drawing.Color.Transparent;
            this.LabelOrderedCocktailName.Font = new System.Drawing.Font("Verdana", 8.25F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.LabelOrderedCocktailName.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(224)))), ((int)(((byte)(224)))), ((int)(((byte)(224)))));
            this.LabelOrderedCocktailName.ImageAlign = System.Drawing.ContentAlignment.BottomRight;
            this.LabelOrderedCocktailName.Location = new System.Drawing.Point(258, 32);
            this.LabelOrderedCocktailName.Name = "LabelOrderedCocktailName";
            this.LabelOrderedCocktailName.Size = new System.Drawing.Size(213, 18);
            this.LabelOrderedCocktailName.TabIndex = 7;
            this.LabelOrderedCocktailName.TextAlign = System.Drawing.ContentAlignment.BottomRight;
            // 
            // groupBoxErrorOrder
            // 
            this.groupBoxErrorOrder.Controls.Add(this.textBoxErrorOrder);
            this.groupBoxErrorOrder.ForeColor = System.Drawing.Color.Red;
            this.groupBoxErrorOrder.Location = new System.Drawing.Point(17, 388);
            this.groupBoxErrorOrder.Name = "groupBoxErrorOrder";
            this.groupBoxErrorOrder.Size = new System.Drawing.Size(454, 86);
            this.groupBoxErrorOrder.TabIndex = 8;
            this.groupBoxErrorOrder.TabStop = false;
            this.groupBoxErrorOrder.Text = "Fehler bei der Bestellung";
            this.groupBoxErrorOrder.Visible = false;
            // 
            // textBoxErrorOrder
            // 
            this.textBoxErrorOrder.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBoxErrorOrder.ForeColor = System.Drawing.Color.Red;
            this.textBoxErrorOrder.Location = new System.Drawing.Point(6, 19);
            this.textBoxErrorOrder.Multiline = true;
            this.textBoxErrorOrder.Name = "textBoxErrorOrder";
            this.textBoxErrorOrder.Size = new System.Drawing.Size(442, 61);
            this.textBoxErrorOrder.TabIndex = 0;
            // 
            // PortletOrder
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.BackColor = System.Drawing.Color.Transparent;
            this.Controls.Add(this.groupBoxErrorOrder);
            this.Controls.Add(this.LabelOrderedCocktailName);
            this.Controls.Add(this.groupBoxWishes);
            this.Controls.Add(this.buttonAbbrechen);
            this.Controls.Add(this.buttonBestellen);
            this.Name = "PortletOrder";
            this.Load += new System.EventHandler(this.PortletOrder_Load);
            this.groupBoxWishes.ResumeLayout(false);
            this.groupBoxWishes.PerformLayout();
            this.groupBoxErrorOrder.ResumeLayout(false);
            this.groupBoxErrorOrder.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button buttonBestellen;
        private System.Windows.Forms.Button buttonAbbrechen;
        private System.Windows.Forms.GroupBox groupBoxWishes;
        private System.Windows.Forms.TextBox textBoxWishes;
        private System.Windows.Forms.Label LabelOrderedCocktailName;
        private System.Windows.Forms.GroupBox groupBoxErrorOrder;
        private System.Windows.Forms.TextBox textBoxErrorOrder;

    }
}
