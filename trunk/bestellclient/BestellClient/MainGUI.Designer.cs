namespace BestellClient
{
    partial class MainGUI
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(MainGUI));
            this.labelUser = new System.Windows.Forms.Label();
            this.labelBill = new System.Windows.Forms.Label();
            this.timer1 = new System.Windows.Forms.Timer(this.components);
            this.buttonLogOut = new System.Windows.Forms.Button();
            this.pictureBoxLogoTop = new System.Windows.Forms.PictureBox();
            this.groupBoxMessage = new System.Windows.Forms.GroupBox();
            this.textBoxMessage = new System.Windows.Forms.TextBox();
            this.timerMesssage = new System.Windows.Forms.Timer(this.components);
            this.timerAutomaticLogoutcheck = new System.Windows.Forms.Timer(this.components);
            this.sliderContainer1 = new BestellClient.SliderContainer();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogoTop)).BeginInit();
            this.groupBoxMessage.SuspendLayout();
            this.SuspendLayout();
            // 
            // labelUser
            // 
            this.labelUser.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Bold | System.Drawing.FontStyle.Italic))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelUser.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.labelUser.ImageAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.labelUser.Location = new System.Drawing.Point(647, 9);
            this.labelUser.Name = "labelUser";
            this.labelUser.Size = new System.Drawing.Size(279, 20);
            this.labelUser.TabIndex = 3;
            this.labelUser.Text = "User wird geladen ...";
            this.labelUser.TextAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.labelUser.Visible = false;
            // 
            // labelBill
            // 
            this.labelBill.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Italic, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelBill.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.labelBill.ImageAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.labelBill.Location = new System.Drawing.Point(651, 36);
            this.labelBill.Name = "labelBill";
            this.labelBill.Size = new System.Drawing.Size(275, 13);
            this.labelBill.TabIndex = 4;
            this.labelBill.Text = "Rechnungsbetrag wird geladen ...";
            this.labelBill.TextAlign = System.Drawing.ContentAlignment.MiddleRight;
            this.labelBill.Visible = false;
            // 
            // timer1
            // 
            this.timer1.Interval = 2000;
            this.timer1.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // buttonLogOut
            // 
            this.buttonLogOut.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.buttonLogOut.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.buttonLogOut.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.buttonLogOut.Location = new System.Drawing.Point(932, 12);
            this.buttonLogOut.Name = "buttonLogOut";
            this.buttonLogOut.Size = new System.Drawing.Size(75, 23);
            this.buttonLogOut.TabIndex = 2;
            this.buttonLogOut.Text = "Logout";
            this.buttonLogOut.UseVisualStyleBackColor = true;
            this.buttonLogOut.Visible = false;
            this.buttonLogOut.MouseClick += new System.Windows.Forms.MouseEventHandler(this.buttonLogOut_MouseClick);
            // 
            // pictureBoxLogoTop
            // 
            this.pictureBoxLogoTop.Image = ((System.Drawing.Image)(resources.GetObject("pictureBoxLogoTop.Image")));
            this.pictureBoxLogoTop.Location = new System.Drawing.Point(8, -3);
            this.pictureBoxLogoTop.Name = "pictureBoxLogoTop";
            this.pictureBoxLogoTop.Size = new System.Drawing.Size(332, 53);
            this.pictureBoxLogoTop.TabIndex = 1;
            this.pictureBoxLogoTop.TabStop = false;
            // 
            // groupBoxMessage
            // 
            this.groupBoxMessage.BackColor = System.Drawing.Color.White;
            this.groupBoxMessage.Controls.Add(this.textBoxMessage);
            this.groupBoxMessage.Location = new System.Drawing.Point(380, 263);
            this.groupBoxMessage.Name = "groupBoxMessage";
            this.groupBoxMessage.Size = new System.Drawing.Size(309, 151);
            this.groupBoxMessage.TabIndex = 5;
            this.groupBoxMessage.TabStop = false;
            this.groupBoxMessage.Text = "Meldung";
            this.groupBoxMessage.Visible = false;
            // 
            // textBoxMessage
            // 
            this.textBoxMessage.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBoxMessage.Font = new System.Drawing.Font("Microsoft Sans Serif", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.textBoxMessage.Location = new System.Drawing.Point(7, 20);
            this.textBoxMessage.Multiline = true;
            this.textBoxMessage.Name = "textBoxMessage";
            this.textBoxMessage.Size = new System.Drawing.Size(296, 125);
            this.textBoxMessage.TabIndex = 0;
            this.textBoxMessage.Visible = false;
            // 
            // timerMesssage
            // 
            this.timerMesssage.Interval = 4000;
            this.timerMesssage.Tick += new System.EventHandler(this.timerMesssage_Tick);
            // 
            // timerAutomaticLogoutcheck
            // 
            this.timerAutomaticLogoutcheck.Enabled = true;
            this.timerAutomaticLogoutcheck.Interval = 1000;
            this.timerAutomaticLogoutcheck.Tick += new System.EventHandler(this.timerAutomaticLogoutcheck_Tick);
            // 
            // sliderContainer1
            // 
            this.sliderContainer1.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.sliderContainer1.Location = new System.Drawing.Point(8, 56);
            this.sliderContainer1.Name = "sliderContainer1";
            this.sliderContainer1.Size = new System.Drawing.Size(1000, 500);
            this.sliderContainer1.TabIndex = 0;
            this.sliderContainer1.Load += new System.EventHandler(this.sliderContainer1_Load);
            // 
            // MainGUI
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(152)))), ((int)(((byte)(18)))), ((int)(((byte)(103)))));
            this.ClientSize = new System.Drawing.Size(1020, 566);
            this.Controls.Add(this.groupBoxMessage);
            this.Controls.Add(this.labelBill);
            this.Controls.Add(this.labelUser);
            this.Controls.Add(this.buttonLogOut);
            this.Controls.Add(this.pictureBoxLogoTop);
            this.Controls.Add(this.sliderContainer1);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "MainGUI";
            this.Text = "Cocktailberater - Bestellungen";
            this.Load += new System.EventHandler(this.MainGUI_Load);
            this.SizeChanged += new System.EventHandler(this.MainGUI_SizeChanged);
            this.Click += new System.EventHandler(this.MainGUI_Click);
            this.Resize += new System.EventHandler(this.MainGUI_Resize);
            this.ResizeEnd += new System.EventHandler(this.MainGUI_ResizeEnd);
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogoTop)).EndInit();
            this.groupBoxMessage.ResumeLayout(false);
            this.groupBoxMessage.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private SliderContainer sliderContainer1;
        private System.Windows.Forms.PictureBox pictureBoxLogoTop;
        private System.Windows.Forms.Button buttonLogOut;
        private System.Windows.Forms.Label labelUser;
        private System.Windows.Forms.Label labelBill;
        private System.Windows.Forms.Timer timer1;
        private System.Windows.Forms.GroupBox groupBoxMessage;
        private System.Windows.Forms.TextBox textBoxMessage;
        private System.Windows.Forms.Timer timerMesssage;
        private System.Windows.Forms.Timer timerAutomaticLogoutcheck;

    }
}