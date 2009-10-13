using System;
using System.Collections.Generic;
using System.Text;

namespace DTO
{
    public class User
    {
        private String username;

        public String Username
        {
            get {
                if (!IsLogedIn) throw new Exception("User not logged id");
                return username; }
            set { username = value; }
        }
        private bool isLogedIn;

        public bool IsLogedIn
        {
            get { return isLogedIn; }
            set { isLogedIn = value; }
        }
        private String userId;

        public String UserId
        {
            get { if (!IsLogedIn) throw new Exception("User not logged id"); 
                return userId; }
            set { userId = value; }
        }

        public User()
        {
            IsLogedIn = false;
            userId = "";
            username = "";
        }

        private string billSum;

        public string BillSum
        {
            get { return billSum; }
            set { billSum = value; }
        }

        private string hashCode;

        public string HashCode
        {
            get { return hashCode; }
            set { hashCode = value; }
        }
        private string firstname;

        public string Firstname
        {
            get { return firstname; }
            set { firstname = value; }
        }
        private string lastname;

        public string Lastname
        {
            get { return lastname; }
            set { lastname = value; }
        }

    }
}
