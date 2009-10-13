namespace BestellClient
{
    partial class PortletLogIn
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
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.textBoxName = new System.Windows.Forms.TextBox();
            this.textBoxPassword = new System.Windows.Forms.MaskedTextBox();
            this.buttonLogin = new System.Windows.Forms.Button();
            this.LabelErrorLogIn = new System.Windows.Forms.Label();
            this.pictureBoxLogoLogIn = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorLogInName = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorLogInPwd = new System.Windows.Forms.PictureBox();
            this.groupBoxErrorLogIn = new System.Windows.Forms.GroupBox();
            this.textBoxErrorLogIn = new System.Windows.Forms.TextBox();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogoLogIn)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogInName)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogInPwd)).BeginInit();
            this.groupBoxErrorLogIn.SuspendLayout();
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(128, 204);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(35, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "Name";
            this.label1.Visible = false;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(128, 250);
            this.label2.Name = "label2";
            this.label2.RightToLeft = System.Windows.Forms.RightToLeft.No;
            this.label2.Size = new System.Drawing.Size(50, 13);
            this.label2.TabIndex = 1;
            this.label2.Text = "Passwort";
            this.label2.Visible = false;
            // 
            // textBoxName
            // 
            this.textBoxName.Location = new System.Drawing.Point(201, 201);
            this.textBoxName.Name = "textBoxName";
            this.textBoxName.Size = new System.Drawing.Size(100, 20);
            this.textBoxName.TabIndex = 2;
            this.textBoxName.Visible = false;
            this.textBoxName.TextChanged += new System.EventHandler(this.textBoxName_TextChanged);
            // 
            // textBoxPassword
            // 
            this.textBoxPassword.Location = new System.Drawing.Point(201, 247);
            this.textBoxPassword.Name = "textBoxPassword";
            this.textBoxPassword.PasswordChar = '*';
            this.textBoxPassword.Size = new System.Drawing.Size(100, 20);
            this.textBoxPassword.TabIndex = 3;
            this.textBoxPassword.Visible = false;
            this.textBoxPassword.MaskInputRejected += new System.Windows.Forms.MaskInputRejectedEventHandler(this.textBoxPassword_MaskInputRejected);
            this.textBoxPassword.KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.textBoxPassword_KeyPress);
            this.textBoxPassword.TextChanged += new System.EventHandler(this.textBoxPassword_TextChanged);
            // 
            // buttonLogin
            // 
            this.buttonLogin.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.buttonLogin.ForeColor = System.Drawing.SystemColors.ControlLightLight;
            this.buttonLogin.Location = new System.Drawing.Point(201, 297);
            this.buttonLogin.Name = "buttonLogin";
            this.buttonLogin.Size = new System.Drawing.Size(100, 23);
            this.buttonLogin.TabIndex = 4;
            this.buttonLogin.Text = "Login";
            this.buttonLogin.UseVisualStyleBackColor = true;
            this.buttonLogin.Visible = false;
            this.buttonLogin.Click += new System.EventHandler(this.buttonLogin_Click);
            // 
            // LabelErrorLogIn
            // 
            this.LabelErrorLogIn.AutoSize = true;
            this.LabelErrorLogIn.Location = new System.Drawing.Point(128, 341);
            this.LabelErrorLogIn.Name = "LabelErrorLogIn";
            this.LabelErrorLogIn.Size = new System.Drawing.Size(0, 13);
            this.LabelErrorLogIn.TabIndex = 5;
            this.LabelErrorLogIn.Visible = false;
            // 
            // pictureBoxLogoLogIn
            // 
            this.pictureBoxLogoLogIn.Image = global::BestellClient.Resource1.Bildmarke;
            this.pictureBoxLogoLogIn.Location = new System.Drawing.Point(108, 16);
            this.pictureBoxLogoLogIn.Name = "pictureBoxLogoLogIn";
            this.pictureBoxLogoLogIn.Size = new System.Drawing.Size(272, 458);
            this.pictureBoxLogoLogIn.TabIndex = 6;
            this.pictureBoxLogoLogIn.TabStop = false;
            // 
            // pictureBoxErrorLogInName
            // 
            this.pictureBoxErrorLogInName.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorLogInName.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorLogInName.Location = new System.Drawing.Point(329, 201);
            this.pictureBoxErrorLogInName.Name = "pictureBoxErrorLogInName";
            this.pictureBoxErrorLogInName.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorLogInName.TabIndex = 7;
            this.pictureBoxErrorLogInName.TabStop = false;
            this.pictureBoxErrorLogInName.Visible = false;
            // 
            // pictureBoxErrorLogInPwd
            // 
            this.pictureBoxErrorLogInPwd.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorLogInPwd.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorLogInPwd.Location = new System.Drawing.Point(329, 246);
            this.pictureBoxErrorLogInPwd.Name = "pictureBoxErrorLogInPwd";
            this.pictureBoxErrorLogInPwd.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorLogInPwd.TabIndex = 8;
            this.pictureBoxErrorLogInPwd.TabStop = false;
            this.pictureBoxErrorLogInPwd.Visible = false;
            // 
            // groupBoxErrorLogIn
            // 
            this.groupBoxErrorLogIn.Controls.Add(this.textBoxErrorLogIn);
            this.groupBoxErrorLogIn.ForeColor = System.Drawing.Color.Red;
            this.groupBoxErrorLogIn.Location = new System.Drawing.Point(121, 357);
            this.groupBoxErrorLogIn.Name = "groupBoxErrorLogIn";
            this.groupBoxErrorLogIn.Size = new System.Drawing.Size(259, 73);
            this.groupBoxErrorLogIn.TabIndex = 9;
            this.groupBoxErrorLogIn.TabStop = false;
            this.groupBoxErrorLogIn.Text = "Fehler beim LogIn";
            this.groupBoxErrorLogIn.Visible = false;
            // 
            // textBoxErrorLogIn
            // 
            this.textBoxErrorLogIn.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBoxErrorLogIn.ForeColor = System.Drawing.Color.Red;
            this.textBoxErrorLogIn.Location = new System.Drawing.Point(10, 19);
            this.textBoxErrorLogIn.Multiline = true;
            this.textBoxErrorLogIn.Name = "textBoxErrorLogIn";
            this.textBoxErrorLogIn.Size = new System.Drawing.Size(243, 48);
            this.textBoxErrorLogIn.TabIndex = 0;
            // 
            // PortletLogIn
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.Controls.Add(this.groupBoxErrorLogIn);
            this.Controls.Add(this.pictureBoxErrorLogInPwd);
            this.Controls.Add(this.pictureBoxErrorLogInName);
            this.Controls.Add(this.pictureBoxLogoLogIn);
            this.Controls.Add(this.LabelErrorLogIn);
            this.Controls.Add(this.buttonLogin);
            this.Controls.Add(this.textBoxPassword);
            this.Controls.Add(this.textBoxName);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Name = "PortletLogIn";
            this.Load += new System.EventHandler(this.PortletLogIn_Load);
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxLogoLogIn)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogInName)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogInPwd)).EndInit();
            this.groupBoxErrorLogIn.ResumeLayout(false);
            this.groupBoxErrorLogIn.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox textBoxName;
        private System.Windows.Forms.MaskedTextBox textBoxPassword;
        private System.Windows.Forms.Button buttonLogin;
        private System.Windows.Forms.Label LabelErrorLogIn;
        private System.Windows.Forms.PictureBox pictureBoxLogoLogIn;
        private System.Windows.Forms.PictureBox pictureBoxErrorLogInName;
        private System.Windows.Forms.PictureBox pictureBoxErrorLogInPwd;
        private System.Windows.Forms.GroupBox groupBoxErrorLogIn;
        private System.Windows.Forms.TextBox textBoxErrorLogIn;

    }
}
