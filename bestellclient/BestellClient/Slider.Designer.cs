namespace BestellClient
{
    partial class Slider
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

        #region Component Designer generated code

        /// <summary> 
        /// Required method for Designer support - do not modify 
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.portletOrder1 = new BestellClient.PortletOrder();
            this.portletDetails1 = new BestellClient.PortletDetails();
            this.portletSearch1 = new BestellClient.PortletSearch();
            this.portletLogIn1 = new BestellClient.PortletLogIn();
            this.portletLogo1 = new BestellClient.PortletLogo();
            this.portletRegister1 = new BestellClient.PortletRegister();
            this.SuspendLayout();
            // 
            // portletOrder1
            // 
            this.portletOrder1.BackColor = System.Drawing.Color.White;
            this.portletOrder1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletOrder1.Location = new System.Drawing.Point(2000, 0);
            this.portletOrder1.Name = "portletOrder1";
            this.portletOrder1.Size = new System.Drawing.Size(500, 500);
            this.portletOrder1.TabIndex = 4;
            // 
            // portletDetails1
            // 
            this.portletDetails1.BackColor = System.Drawing.Color.White;
            this.portletDetails1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletDetails1.Location = new System.Drawing.Point(1500, 0);
            this.portletDetails1.Name = "portletDetails1";
            this.portletDetails1.Size = new System.Drawing.Size(500, 500);
            this.portletDetails1.TabIndex = 3;
            // 
            // portletSearch1
            // 
            this.portletSearch1.BackColor = System.Drawing.Color.White;
            this.portletSearch1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletSearch1.Location = new System.Drawing.Point(1000, 0);
            this.portletSearch1.Name = "portletSearch1";
            this.portletSearch1.Size = new System.Drawing.Size(500, 500);
            this.portletSearch1.TabIndex = 2;
            // 
            // portletLogIn1
            // 
            this.portletLogIn1.BackColor = System.Drawing.Color.White;
            this.portletLogIn1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletLogIn1.Location = new System.Drawing.Point(500, 0);
            this.portletLogIn1.Margin = new System.Windows.Forms.Padding(0);
            this.portletLogIn1.Name = "portletLogIn1";
            this.portletLogIn1.Size = new System.Drawing.Size(500, 500);
            this.portletLogIn1.TabIndex = 1;
            this.portletLogIn1.Load += new System.EventHandler(this.portletLogIn1_Load);
            // 
            // portletLogo1
            // 
            this.portletLogo1.BackColor = System.Drawing.Color.White;
            this.portletLogo1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletLogo1.Location = new System.Drawing.Point(0, 0);
            this.portletLogo1.Margin = new System.Windows.Forms.Padding(0);
            this.portletLogo1.Name = "portletLogo1";
            this.portletLogo1.Size = new System.Drawing.Size(500, 500);
            this.portletLogo1.TabIndex = 0;
            // 
            // portletRegister1
            // 
            this.portletRegister1.BackColor = System.Drawing.Color.White;
            this.portletRegister1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.portletRegister1.Location = new System.Drawing.Point(0, 0);
            this.portletRegister1.Name = "portletRegister1";
            this.portletRegister1.Size = new System.Drawing.Size(500, 500);
            this.portletRegister1.TabIndex = 5;
            this.portletRegister1.Visible = false;
            // 
            // Slider
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.White;
            this.Controls.Add(this.portletRegister1);
            this.Controls.Add(this.portletOrder1);
            this.Controls.Add(this.portletDetails1);
            this.Controls.Add(this.portletSearch1);
            this.Controls.Add(this.portletLogIn1);
            this.Controls.Add(this.portletLogo1);
            this.Name = "Slider";
            this.Size = new System.Drawing.Size(2500, 500);
            this.ResumeLayout(false);

        }

        #endregion

        private PortletLogo portletLogo1;
        private PortletLogIn portletLogIn1;
        private PortletSearch portletSearch1;
        private PortletDetails portletDetails1;
        private PortletOrder portletOrder1;
        private PortletRegister portletRegister1;
    }
}
