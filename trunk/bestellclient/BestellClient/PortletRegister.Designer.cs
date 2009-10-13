namespace BestellClient
{
    partial class PortletRegister
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
            this.label1 = new System.Windows.Forms.Label();
            this.textBox1 = new System.Windows.Forms.TextBox();
            this.label2 = new System.Windows.Forms.Label();
            this.textBox2 = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.label5 = new System.Windows.Forms.Label();
            this.maskedTextBox1 = new System.Windows.Forms.MaskedTextBox();
            this.textBox3 = new System.Windows.Forms.TextBox();
            this.textBox4 = new System.Windows.Forms.TextBox();
            this.button1 = new System.Windows.Forms.Button();
            this.pictureBoxErrorFirstname = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorLogLastName = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorEmail = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorPW = new System.Windows.Forms.PictureBox();
            this.pictureBoxErrorBirthday = new System.Windows.Forms.PictureBox();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.textBoxError = new System.Windows.Forms.TextBox();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorFirstname)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogLastName)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorEmail)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorPW)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorBirthday)).BeginInit();
            this.groupBox1.SuspendLayout();
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(126, 118);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(52, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "Vorname:";
            // 
            // textBox1
            // 
            this.textBox1.Location = new System.Drawing.Point(206, 111);
            this.textBox1.Name = "textBox1";
            this.textBox1.Size = new System.Drawing.Size(161, 20);
            this.textBox1.TabIndex = 1;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(126, 155);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(62, 13);
            this.label2.TabIndex = 2;
            this.label2.Text = "Nachname:";
            // 
            // textBox2
            // 
            this.textBox2.Location = new System.Drawing.Point(206, 148);
            this.textBox2.Name = "textBox2";
            this.textBox2.Size = new System.Drawing.Size(161, 20);
            this.textBox2.TabIndex = 3;
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(126, 193);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(76, 13);
            this.label3.TabIndex = 4;
            this.label3.Text = "Email Adresse:";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(126, 227);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(56, 13);
            this.label4.TabIndex = 5;
            this.label4.Text = "Password:";
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(126, 264);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(62, 13);
            this.label5.TabIndex = 7;
            this.label5.Text = "Geburtstag:";
            // 
            // maskedTextBox1
            // 
            this.maskedTextBox1.Location = new System.Drawing.Point(206, 257);
            this.maskedTextBox1.Mask = "00/00/0000";
            this.maskedTextBox1.Name = "maskedTextBox1";
            this.maskedTextBox1.Size = new System.Drawing.Size(66, 20);
            this.maskedTextBox1.TabIndex = 9;
            this.maskedTextBox1.ValidatingType = typeof(System.DateTime);
            this.maskedTextBox1.MaskInputRejected += new System.Windows.Forms.MaskInputRejectedEventHandler(this.maskedTextBox1_MaskInputRejected);
            // 
            // textBox3
            // 
            this.textBox3.Location = new System.Drawing.Point(206, 186);
            this.textBox3.Name = "textBox3";
            this.textBox3.Size = new System.Drawing.Size(161, 20);
            this.textBox3.TabIndex = 5;
            // 
            // textBox4
            // 
            this.textBox4.Location = new System.Drawing.Point(206, 220);
            this.textBox4.Name = "textBox4";
            this.textBox4.Size = new System.Drawing.Size(161, 20);
            this.textBox4.TabIndex = 7;
            // 
            // button1
            // 
            this.button1.BackgroundImage = global::BestellClient.Resource1.buttonLila;
            this.button1.ForeColor = System.Drawing.Color.White;
            this.button1.Location = new System.Drawing.Point(206, 305);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(75, 23);
            this.button1.TabIndex = 11;
            this.button1.Text = "Register";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // pictureBoxErrorFirstname
            // 
            this.pictureBoxErrorFirstname.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorFirstname.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorFirstname.Location = new System.Drawing.Point(373, 110);
            this.pictureBoxErrorFirstname.Name = "pictureBoxErrorFirstname";
            this.pictureBoxErrorFirstname.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorFirstname.TabIndex = 12;
            this.pictureBoxErrorFirstname.TabStop = false;
            this.pictureBoxErrorFirstname.Visible = false;
            // 
            // pictureBoxErrorLogLastName
            // 
            this.pictureBoxErrorLogLastName.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorLogLastName.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorLogLastName.Location = new System.Drawing.Point(373, 147);
            this.pictureBoxErrorLogLastName.Name = "pictureBoxErrorLogLastName";
            this.pictureBoxErrorLogLastName.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorLogLastName.TabIndex = 13;
            this.pictureBoxErrorLogLastName.TabStop = false;
            this.pictureBoxErrorLogLastName.Visible = false;
            // 
            // pictureBoxErrorEmail
            // 
            this.pictureBoxErrorEmail.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorEmail.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorEmail.Location = new System.Drawing.Point(373, 185);
            this.pictureBoxErrorEmail.Name = "pictureBoxErrorEmail";
            this.pictureBoxErrorEmail.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorEmail.TabIndex = 14;
            this.pictureBoxErrorEmail.TabStop = false;
            this.pictureBoxErrorEmail.Visible = false;
            // 
            // pictureBoxErrorPW
            // 
            this.pictureBoxErrorPW.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorPW.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorPW.Location = new System.Drawing.Point(373, 219);
            this.pictureBoxErrorPW.Name = "pictureBoxErrorPW";
            this.pictureBoxErrorPW.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorPW.TabIndex = 15;
            this.pictureBoxErrorPW.TabStop = false;
            this.pictureBoxErrorPW.Visible = false;
            // 
            // pictureBoxErrorBirthday
            // 
            this.pictureBoxErrorBirthday.BackgroundImage = global::BestellClient.Resource1.error;
            this.pictureBoxErrorBirthday.BackgroundImageLayout = System.Windows.Forms.ImageLayout.Stretch;
            this.pictureBoxErrorBirthday.Location = new System.Drawing.Point(373, 256);
            this.pictureBoxErrorBirthday.Name = "pictureBoxErrorBirthday";
            this.pictureBoxErrorBirthday.Size = new System.Drawing.Size(25, 21);
            this.pictureBoxErrorBirthday.TabIndex = 16;
            this.pictureBoxErrorBirthday.TabStop = false;
            this.pictureBoxErrorBirthday.Visible = false;
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.textBoxError);
            this.groupBox1.ForeColor = System.Drawing.Color.Red;
            this.groupBox1.Location = new System.Drawing.Point(129, 347);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(238, 100);
            this.groupBox1.TabIndex = 17;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "Fehler bei der Registrierung";
            this.groupBox1.Visible = false;
            // 
            // textBoxError
            // 
            this.textBoxError.BackColor = System.Drawing.SystemColors.Window;
            this.textBoxError.BorderStyle = System.Windows.Forms.BorderStyle.None;
            this.textBoxError.Dock = System.Windows.Forms.DockStyle.Fill;
            this.textBoxError.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.textBoxError.ForeColor = System.Drawing.Color.Red;
            this.textBoxError.Location = new System.Drawing.Point(3, 16);
            this.textBoxError.Multiline = true;
            this.textBoxError.Name = "textBoxError";
            this.textBoxError.ReadOnly = true;
            this.textBoxError.Size = new System.Drawing.Size(232, 81);
            this.textBoxError.TabIndex = 0;
            // 
            // PortletRegister
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.pictureBoxErrorBirthday);
            this.Controls.Add(this.pictureBoxErrorPW);
            this.Controls.Add(this.pictureBoxErrorEmail);
            this.Controls.Add(this.pictureBoxErrorLogLastName);
            this.Controls.Add(this.pictureBoxErrorFirstname);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.textBox4);
            this.Controls.Add(this.textBox3);
            this.Controls.Add(this.maskedTextBox1);
            this.Controls.Add(this.label5);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.textBox2);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.textBox1);
            this.Controls.Add(this.label1);
            this.Name = "PortletRegister";
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorFirstname)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorLogLastName)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorEmail)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorPW)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBoxErrorBirthday)).EndInit();
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.TextBox textBox1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox textBox2;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.MaskedTextBox maskedTextBox1;
        private System.Windows.Forms.TextBox textBox3;
        private System.Windows.Forms.TextBox textBox4;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.PictureBox pictureBoxErrorFirstname;
        private System.Windows.Forms.PictureBox pictureBoxErrorLogLastName;
        private System.Windows.Forms.PictureBox pictureBoxErrorEmail;
        private System.Windows.Forms.PictureBox pictureBoxErrorPW;
        private System.Windows.Forms.PictureBox pictureBoxErrorBirthday;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.TextBox textBoxError;
    }
}
