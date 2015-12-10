import sys, getopt, os
class const:
	folder_name="Php"
	relative_path="../"
def main(argv):
    
    PHP_PATH = const.relative_path+const.folder_name+"/"
    CONNECTVARS_PATH = PHP_PATH + "connectvars.php"
    
    if len(argv) < 5:
        usage()
        sys.exit(-1)
    elif len(argv) == 5:
        # default positional arguments
        dbHost = argv[1]
        dbName = argv[2]
        dbUser = argv[3]
        dbPass = argv[4]  
    elif len(argv) != 9:
        usage()
        sys.exit(-1)
    
    try:
        opts, args = getopt.getopt(argv[1:], "hd:u:p:s:", ["help", "db-name=", "db-user=", "db-host=", "db-pass="])
    except getopt.GetoptError:
        usage()
        sys.exit(-1)
        
    for opt, arg in opts:
        if opt in ("-h", "--help"):
            usage()
            sys.exit()
        elif opt in ("-d", "db-name"):
            dbName = arg
        elif opt in ("-p", "db-pass"):
            dbPass = arg
        elif opt in ("-u", "db-user"):
            dbUser = arg
        elif opt in ("-s", "db-host"):
            dbHost = arg
            
    if(not os.path.exists(PHP_PATH)):
        os.mkdir(PHP_PATH)
    
    f = open(const.relative_path+const.folder_name+"/connectvars.php", "w")
    write_connectvars_file(f, dbHost, dbName, dbUser, dbPass)    
    f.close()
   
  
def usage():
    print("Usage:")
    print("connect.py -s <databasehost> -d <databasename> -u <databaseuser> -p <databasepassword>")

def get_script_gen_msg():
    s = "/*********************************************** \n"
    s += "This file has been generated with \"connect.py\".\n"
    s += "Go to \"/scripts/connect.py\" for source.\n"
    s += "***********************************************/\n"
    return s
    
def write_connectvars_file(file, dbHost, dbName, dbUser, dbPass):
    file.write("<?php\n")
    file.write(get_script_gen_msg())
    
    file.write("define('DB_HOST', " + dbHost + ");\n")
    file.write("define('DB_NAME', " + dbName + ");\n")
    file.write("define('DB_USER', " + dbUser + ");\n")
    file.write("define('DB_PASS', " + dbPass + ");\n")
    
    file.write("?>\n") 

if __name__ == "__main__":
    main(sys.argv)
