#include "assembler.h"
#include <cstring>

int main(int argc, char *argv[])
{
    
    if (argc != 2 && argc != 3)
    {
        std::cerr << "Usage : ./8085assembler file [-n]\n";
        return 1;
    }

    bool printNewline;
    if (argc == 3 && strcmp(argv[2], "-n") == 0)
        printNewline = true;
    else
        printNewline = false;

    Assembler a(argv[1], printNewline);
 
    return a.exec();
}
