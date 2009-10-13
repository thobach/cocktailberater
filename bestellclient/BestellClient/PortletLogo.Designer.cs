namespace BestellClient
{
    partial class PortletLogo
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
            this.labelLocation = new System.Windows.Forms.Label();
            this.pictureBoxErrorLocation = new System.Windows.Forms.PictureBox();
            this.buttonLocation = new System.Windows.Forms.Button();
            this.pictureBoxLogo = new System.Windows.Forms.PictureBox();
            this.labelErrorLocation = new System.Windows.Forms.Label();
            this.groupBoxErrorLocation = new System.Windows.Forms.GroupBox();
            this.listBoxLocation = new System.Windows.Forms.ListBox();
            this.contextMenuStrip1 = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.toolStripMenuItem1 = new System.Windows.Forms.ToolStripMenuItem();
            this.groupBoxLocation = new System.Windows.Forms.GroupBox();
            this.button1 = new System.Windows.Forms.Button();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLocation)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogo)).BeginInit();
            this.groupBoxErrorLocation.SuspendLayout();
            this.contextMenuStrip1.SuspendLayout();
            this.groupBoxLocation.SuspendLayout();
            this.SuspendLayout();
            // 
            // labelLocation
            // 
            this.labelLocation.Font = new System.Drawing.Font("Verdana", 21.75F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelLocation.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.labelLocation.Location = new System.Drawing.Point(41, 77);
            this.labelLocation.Name = "labelLocation";
            this.labelLocation.Size = new System.Drawing.Size(413, 27);
            this.labelLocation.TabIndex = 3;
            this.labelLocation.Text = "Bitte Location wählen ...";
            // 
            // pictureBoxErrorLocation
            // 
            this.pictureBoxErrorLocation.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorLocation.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorLocation.Location = new System.Drawing.Point(265, 19);
            this.pictureBoxErrorLocation.Name = "pictureBoxErrorLocation";
            this.pictureBoxErrorLocation.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorLocation.TabIndex = 4;
            this.pictureBoxErrorLocation.TabStop = false;
            this.pictureBoxErrorLocation.Visible = false;
            // 
            // buttonLocation
            // 
            this.buttonLocation.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.buttonLocation.ForeColor = System.Drawing.SystemColors.ControlLightLight;
            this.buttonLocation.Location = new System.Drawing.Point(215, 223);
            this.buttonLocation.Name = "buttonLocation";
            this.buttonLocation.Size = new System.Drawing.Size(75, 23);
            this.buttonLocation.TabIndex = 2;
            this.buttonLocation.Text = "auswählen";
            this.buttonLocation.UseVisualStyleBackColor = true;
            this.buttonLocation.MouseClick += new System.Windows.Forms.MouseEventHandler(this.buttonLocation_MouseClick);
            // 
            // pictureBoxLogo
            // 
            this.pictureBoxLogo.Image = global::BestellClient.Resource1.Bildmarke;
            this.pictureBoxLogo.Location = new System.Drawing.Point(88, 15);
            this.pictureBoxLogo.Name = "pictureBoxLogo";
            this.pictureBoxLogo.Size = new System.Drawing.Size(307, 456);
            this.pictureBoxLogo.TabIndex = 0;
            this.pictureBoxLogo.TabStop = false;
            this.pictureBoxLogo.Visible = false;
            // 
            // labelErrorLocation
            // 
            this.labelErrorLocation.AutoSize = true;
            this.labelErrorLocation.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelErrorLocation.ForeColor = System.Drawing.Color.Red;
            this.labelErrorLocation.Location = new System.Drawing.Point(10, 29);
            this.labelErrorLocation.Name = "labelErrorLocation";
            this.labelErrorLocation.Size = new System.Drawing.Size(182, 13);
            this.labelErrorLocation.TabIndex = 5;
            this.labelErrorLocation.Text = "Bitte eine Location auswählen!";
            this.labelErrorLocation.Visible = false;
            // 
            // groupBoxErrorLocation
            // 
            this.groupBoxErrorLocation.Controls.Add(this.labelErrorLocation);
            this.groupBoxErrorLocation.ForeColor = System.Drawing.Color.Red;
            this.groupBoxErrorLocation.Location = new System.Drawing.Point(99, 381);
            this.groupBoxErrorLocation.Name = "groupBoxErrorLocation";
            this.groupBoxErrorLocation.Size = new System.Drawing.Size(296, 79);
            this.groupBoxErrorLocation.TabIndex = 6;
            this.groupBoxErrorLocation.TabStop = false;
            this.groupBoxErrorLocation.Text = "Fehlerhafte Location-Auswahl";
            this.groupBoxErrorLocation.Visible = false;
            // 
            // listBoxLocation
            // 
            this.listBoxLocation.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.listBoxLocation.ContextMenuStrip = this.contextMenuStrip1;
            this.listBoxLocation.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.listBoxLocation.FormattingEnabled = true;
            this.listBoxLocation.Location = new System.Drawing.Point(6, 19);
            this.listBoxLocation.Name = "listBoxLocation";
            this.listBoxLocation.Size = new System.Drawing.Size(253, 195);
            this.listBoxLocation.TabIndex = 7;
            this.listBoxLocation.SelectedIndexChanged += new System.EventHandler(this.listBoxLocation_SelectedIndexChanged);
            // 
            // contextMenuStrip1
            // 
            this.contextMenuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.toolStripMenuItem1});
            this.contextMenuStrip1.Name = "contextMenuStrip1";
            this.contextMenuStrip1.Size = new System.Drawing.Size(147, 26);
            // 
            // toolStripMenuItem1
            // 
            this.toolStripMenuItem1.Name = "toolStripMenuItem1";
            this.toolStripMenuItem1.Size = new System.Drawing.Size(146, 22);
            this.toolStripMenuItem1.Text = "Aktualisieren";
            this.toolStripMenuItem1.Click += new System.EventHandler(this.toolStripMenuItem1_Click);
            // 
            // groupBoxLocation
            // 
            this.groupBoxLocation.Controls.Add(this.listBoxLocation);
            this.groupBoxLocation.Controls.Add(this.pictureBoxErrorLocation);
            this.groupBoxLocation.Controls.Add(this.buttonLocation);
            this.groupBoxLocation.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.groupBoxLocation.Location = new System.Drawing.Point(99, 123);
            this.groupBoxLocation.Name = "groupBoxLocation";
            this.groupBoxLocation.Size = new System.Drawing.Size(296, 252);
            this.groupBoxLocation.TabIndex = 8;
            this.groupBoxLocation.TabStop = false;
            this.groupBoxLocation.Text = "Location";
            // 
            // button1
            // 
            this.button1.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.button1.Location = new System.Drawing.Point(198, 462);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(75, 23);
            this.button1.TabIndex = 9;
            this.button1.Text = "Anmelden";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // PortletLogo
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.groupBoxLocation);
            this.Controls.Add(this.groupBoxErrorLocation);
            this.Controls.Add(this.labelLocation);
            this.Controls.Add(this.pictureBoxLogo);
            this.ForeColor = System.Drawing.Color.White;
            this.Name = "PortletLogo";
            this.Load += new System.EventHandler(this.PortletLogo_Load);
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLocation)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogo)).EndInit();
            this.groupBoxErrorLocation.ResumeLayout(false);
            this.groupBoxErrorLocation.PerformLayout();
            this.contextMenuStrip1.ResumeLayout(false);
            this.groupBoxLocation.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.PictureBox pictureBoxLogo;
        private System.Windows.Forms.Button buttonLocation;
        private System.Windows.Forms.Label labelLocation;
        private System.Windows.Forms.PictureBox pictureBoxErrorLocation;
        private System.Windows.Forms.Label labelErrorLocation;
        private System.Windows.Forms.GroupBox groupBoxErrorLocation;
        private System.Windows.Forms.ListBox listBoxLocation;
        private System.Windows.Forms.GroupBox groupBoxLocation;
        private System.Windows.Forms.ContextMenuStrip contextMenuStrip1;
        private System.Windows.Forms.ToolStripMenuItem toolStripMenuItem1;
        private System.Windows.Forms.Button button1;


    }
}
