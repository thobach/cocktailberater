namespace BestellClient
{
    partial class SliderContainer
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
            this.components = new System.ComponentModel.Container();
            this.timer1 = new System.Windows.Forms.Timer(this.components);
            this.slider1 = new BestellClient.Slider();
            this.SuspendLayout();
            // 
            // timer1
            // 
            this.timer1.Interval = 50;
            this.timer1.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // slider1
            // 
            this.slider1.BackColor = System.Drawing.Color.White;
            this.slider1.Location = new System.Drawing.Point(0, 0);
            this.slider1.Name = "slider1";
            this.slider1.Size = new System.Drawing.Size(2500, 500);
            this.slider1.TabIndex = 0;
            this.slider1.Load += new System.EventHandler(this.slider1_Load);
            // 
            // SliderContainer
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.White;
            this.Controls.Add(this.slider1);
            this.Name = "SliderContainer";
            this.Size = new System.Drawing.Size(1002, 500);
            this.ResumeLayout(false);

        }

        #endregion

        private Slider slider1;
        private System.Windows.Forms.Timer timer1;
    }
}
