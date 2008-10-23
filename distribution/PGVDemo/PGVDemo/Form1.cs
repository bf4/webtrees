using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Diagnostics;
using System.Threading;
using System.IO;

namespace PGVDemo
{
    public partial class Form1 : Form
    {
        Process apache;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            ProcessStartInfo psi = new ProcessStartInfo();
            psi.FileName = ".\\democd\\httpd\\bin\\ApacheCD.exe";
            psi.CreateNoWindow = true;
            psi.UseShellExecute = false;
            psi.RedirectStandardInput = true;
            psi.WorkingDirectory = ".\\democd\\httpd";
            apache = new Process();
            apache.StartInfo = psi;
            apache.Start();
            
            Thread.Sleep(1000);
            //if (!apache.HasExited) Process.Start("http://localhost:6880/index.html");
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (!apache.HasExited)
            {
                try
                {
                    Process.Start("http://localhost:6880/index.html");
                }
                catch (Exception e1)
                {
                    try
                    {
                        Process proc = new Process();
                        proc.StartInfo.FileName = "iexplore";
                        proc.StartInfo.Arguments = "http://localhost:6880/index.html";
                        proc.Start();
                    }
                    catch (Exception e2)
                    {
                        System.Windows.Forms.MessageBox.Show("Unable to launch web browser.\r\nOpen a browser and go to\r\nhttp://localhost:6880/index.html\r\n" + e2.Message);
                    }
                }
            }
            else System.Windows.Forms.MessageBox.Show("Unable to contact apache demo process.\r\nCheck that port 6880 is free and try again.");
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (!apache.HasExited) apache.Kill();
            Thread.Sleep(500);
            Process[] procList = Process.GetProcessesByName("APACHECD");
       
            for (int i = 0; i < procList.Length; i++)
            {
                if (!procList[i].HasExited) procList[i].Kill();
            }
            //Process.Start("\\democd\\httpd\\bin\\Apache.exe -k stop");
            Application.Exit();
        }
    }
}