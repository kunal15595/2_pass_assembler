#include <algorithm>
#include <iterator>
#include <sstream>
#include <utility>      // std::pair, std::get
#include <cstring>
#include <cctype>
#include "iostream"
#include <string>     // std::string, std::to_string
#include "assembler.h"

typedef std::pair<std::string, std::string> string_pair;
typedef std::pair<std::string, int> string_int;

Assembler::Assembler(std::string _file, bool _newline, int org) : fin(_file.c_str()), newline(_newline), line(0), ORG(org)
{
    constructComTable();
    //fin.open(_file);
}

Assembler::~Assembler()
{
    fin.close();
    //
}

int Assembler::exec()
{
    if (pass1() == ERROR)
        return ERROR;

    if (pass2() == ERROR)
        return ERROR;

    std::copy(output.begin(), output.end(), std::ostream_iterator<char>(std::cout));
    if (!newline)
        std::cout << std::endl;
    return OKAY;
}

Signal Assembler::tokenize()
{
    const int BUFFER_SIZE = 256;
    char buffer[BUFFER_SIZE];
    char *token;

    do
    {
        if (fin.eof())
            return END;
        if (fin.fail())
            return ERROR;
        fin.getline(buffer, BUFFER_SIZE);
        line++;
    }while (buffer[0] == ';' || buffer[0] == '\0');
    if (fin.eof())
        return END;

    int i = 0, start, stop;

    labelRead = opcode1Read = opcode2Read = false;

    State state = START;
    Signal signal = OKAY;

    while (state != EXIT)
    {
        while (buffer[i] == ' ')
            i++;
        switch (state)
        {
        case START:
            if (isalpha(buffer[i]))
            {
                start = i;
                while (isalpha(buffer[i]))
                    i++;
                stop = i;

                state = LABEL_COMMAND_REC;
            }
            else
                state = UNEXPECTED;
            break;
        case LABEL_COMMAND_REC:
            if (buffer[i] == ':')
            {
                label.assign(buffer + start, stop - start);
                labelRead = true;
                i++;
                state = COMMAND;
            }
            else if (isalnum(buffer[i]))
            {
                command.assign(buffer + start, stop - start);
                state = OP1;
            }
            else if (buffer[i] == '\0' || buffer[i] == ';')
            {
                command.assign(buffer + start, stop - start);
                state = EXIT;
            }
            else
                state = UNEXPECTED;
            break;
        case COMMAND:
            if (isalpha(buffer[i]))
            {
                start = i;
                while (isalpha(buffer[i]))
                    i++;
                stop = i;
                command.assign(buffer + start, stop - start);
                state = OP1REC;
            }
            else
                state = UNEXPECTED;
            break;
        case OP1REC:
            if (isalnum(buffer[i]))
                state = OP1;
            else if (buffer[i] == '\0' || buffer[i] == ';')
                state = EXIT;
            else
                state = UNEXPECTED;
            break;
        case OP1:
            start = i;
            while (isalnum(buffer[i]))
                i++;
            stop = i;
            op1.assign(buffer + start, stop - start);
            opcode1Read = true;
            state = OP2REC;
            break;
        case OP2REC:
            if (buffer[i] == ',')
            {
                i++;
                state = OP2;
            }
            else if (buffer[i] == '\0' || buffer[i] == ';')
                state = EXIT;
            else
                state = UNEXPECTED;
            break;
        case OP2:
            start = i;
            while (isalnum(buffer[i]))
                i++;
            stop = i;
            op2.assign(buffer + start, stop - start);
            opcode2Read = true;
            state = EXITREC;
            break;
        case EXITREC:
            if (buffer[i] == '\0' || buffer[i] == ';')
                state = EXIT;
            else
                state = UNEXPECTED;
            break;
        case UNEXPECTED:
            signal = ERROR;
            state = EXIT;
            std::cerr << "Parse Error : Unexpected character on line " << line << " at column " << i + 1 << ".\n";
            break;
        }
    }
    return signal;
}

bool Assembler::constructComTable()
{
    typedef CommandOpCode coc;
    typedef std::pair<std::string, coc> com;
    com symbol;

    const int LINE = 64;
    char line[64];
    char *tok;
    std::ifstream symfile("symbols8085.txt");

    while (symfile.getline(line, LINE))
    {
        // assigning opcode
        tok = strtok(line, " ");
        symbol.first.assign(tok);

        // assigning second 
        tok = strtok(NULL, " ");
        if (!isalpha(tok[0]))
            symbol.second.op1 = tok[0] - '0';
        else
            symbol.second.op1 = tok[0];

        // assigning third
        tok = strtok(NULL, " ");
        if (!tok[0])
            symbol.second.op2 = tok[0] - '0';
        else
            symbol.second.op2 = tok[0];

        // assigning fourth
        tok = strtok(NULL, " ");
        symbol.second.setCode(tok);

        // inserting in the table
        std::cout << symbol.first << "::" << symbol.second.op1 << ":" << symbol.second.op2 << ":" << symbol.second.code  << '\n';
        comTable.insert(symbol);
    }

    symfile.close();
    /*
    comTable.insert(com("ADD", coc('A', 0, "87")));
    comTable.insert(com("ADD", coc('B', 0, "80")));
    comTable.insert(com("ADD", coc('C', 0, "81")));
    comTable.insert(com("MOV", coc('A', 'B', "78")));
    comTable.insert(com("MOV", coc('B', 'C', "41")));
    comTable.insert(com("MVI", coc('A', 1, "3E")));
    comTable.insert(com("MVI", coc('B', 1, "06")));
    comTable.insert(com("CMA", coc(0, 0, "2F")));
    comTable.insert(com("HLT", coc(0, 0, "76")));
    comTable.insert(com("ANI", coc(1, 0, "A6")));
    */
}

std::string Assembler::hexAddress(int a)
{
    std::stringstream sstream;

    if (a == -1)
        a = address;
    sstream << std::hex << a;

    std::string s = sstream.str();
    std::transform(s.begin(), s.end(), s.begin(), ::toupper);

    while (s.length() < 4)
    {
        s.insert(0, 1, '0');
    }
    return s;
}

Signal Assembler::pass1()
{
    // ref: http://www.cplusplus.com/reference/map/multimap/
    // multimap works as a hash table
    std::multimap<std::string, CommandOpCode>::iterator it;

    // string_pair::iterator spit;

    // return whether pass1 successfully completed
    Signal ret;


    line = 0;
    address = ORG;
    int output_pos = 0;

    std::cout << "\n------------------------\n" << std::endl;
    
    // repeat until ret is "OKAY"
    while ((ret = tokenize()) == OKAY)
    {
        std::cout << "labelRead : " << labelRead << ", label : " << label << std::endl; 
        if (labelRead)
        {
            if (!(symTable.count(label)))
            {
                // std::cout << "label : " << label << std::endl; 
                symTable.insert(string_pair(label, hexAddress()));
            }
            else
            {
                std::cerr << "Error : label \"" << label << "\" repeated\n";
            }
        }
        // find in command table
        it = comTable.find(command);
        
        // if command found
        if (it != comTable.end())
        {
            // get the op-code from constructed comTable
            CommandOpCode opCode = it->second;

            std::cout << "opCode : " << opCode.op1 << " : " << opCode.op2 << " : " << opCode.code << std::endl; 

            // no op-codes required
            // case 1
            if (opCode.op1 == 0)
            {
                std::cout << "case 1 " << std::endl;
                if (opcode1Read)
                {
                    std::cerr << "Command \"" << command << "\" does not expect opcodes\n";
                    continue;
                }
                // output only opcode of instruction
                output.push_back(opCode.code[0]);
                output.push_back(opCode.code[1]);
                address++;
            }
            // one op-code required
            // case 2
            else if (opCode.op1 == 1)
            {
                std::cout << "case 2 " << std::endl;
                if (!opcode1Read)
                {
                    std::cerr << "Command \"" << command << "\" expects a Byte of data\n";
                    continue;
                }
                // output opcode as well as one byte of data
                output.push_back(opCode.code[0]);
                output.push_back(opCode.code[1]);
                output.push_back(op1[0]);
                output.push_back(op1[1]);
                address += 2;
            }
            // two op-codes required
            // case 3
            else if (opCode.op1 == 2)
            {
                std::cout << "case 3 " << std::endl;
                if (!opcode1Read)
                {
                    std::cerr << "Command \"" << command << "\" expects 2 Bytes of data\n";
                    continue;
                }
                // output opcode and two bytes of data
                output.push_back(opCode.code[0]);
                output.push_back(opCode.code[1]);

                std::string s = "0000";
                std::cout << " " << std::endl;
                if (symTable.count(op1))
                {
                    s = symTable[op1];
                    std::cout << symTable[op1] << "\n";
                }
                else if(!isalpha(opCode.op1)){
                    s = op1;
                    if(s.length()==1)s='0'+s; 
                    std::cout << "string : "<<s << "\n";
                }
                else
                {
                    // label
                    std::cout << "label -> " << op1 << ":" << output.size() << std::endl;
                    labels.insert(string_int(op1, output.size()));
                }
                if(s.length()<3){
                    s[2] = s[3] = '0';
                }
                output.push_back(' ');
                output.push_back(s[0]);
                output.push_back(s[1]);
                output.push_back(' ');
                output.push_back(s[2]);
                output.push_back(s[3]);
                address += 3;
            }
            // op1 is alphabetic and op2 isin't
            // case 4
            else if (opCode.op2 == '0')
            {
                std::cout << "case 4 " << std::endl;
                if (!opcode1Read || opcode2Read)
                {
                    std::cerr << "Command \"" << command << "\" requires exactly 1 opcode\n";
                    continue;
                }
                for (; it != comTable.end(); it++)
                {
                    opCode = it->second;
                    if (opCode.op1 == op1[0])
                    {
                        // output op-code of instruction
                        output.push_back(opCode.code[0]);
                        output.push_back(opCode.code[1]);
                        break;
                    }
                }
                address++;
            }
            // both op1 and op2 are alphabetic
            // case 5
            else if (isalpha(opCode.op1) && isalpha(opCode.op2))
            {
                std::cout << "case 5 " << std::endl;
                if (!opcode2Read)
                {
                    std::cerr << "Command \"" << command << "\" requires 2 opcodes\n";
                    continue;
                }
                for (; it != comTable.end(); it++)
                {
                    opCode = it->second;
                    if (opCode.op1 == op1[0] && opCode.op2 == op2[0])
                    {
                        // 
                        output.push_back(opCode.code[0]);
                        output.push_back(opCode.code[1]);
                        address++;
                        break;
                    }
                }
            }
            // only op1 is alphabetic
            // case 6
            else if (isalpha(opCode.op1) && opCode.op2 == '1')
            {
                std::cout << "case 6 " << std::endl;
                if (!opcode2Read)
                {
                    std::cerr << "Command \"" << command << "\" requires a Register and 1 Byte data\n";
                    continue;
                }
                for (; it != comTable.end(); it++)
                {
                    opCode = it->second;
                    if (opCode.op1 == op1[0])
                    {
                        output.push_back(opCode.code[0]);
                        output.push_back(opCode.code[1]);
                        output.push_back(op2[0]);
                        output.push_back(op2[1]);
                        address += 2;
                        break;
                    }
                }
            }
            // case 7
            else{
                std::cout << "case 7 " << std::endl;
                
            }
            if (newline)
                output.push_back('\n');
            std::cout << "\n------------------------\n" << std::endl;
        }
        else
        {
            std::cerr << "Command not found : " << command << '\n';
        }
    }
    return ret;
}

Signal Assembler::pass2()
{
    std::string s;
    int pos;
    //assign forward labels
    for (std::map<std::string, int>::iterator it = labels.begin(); it != labels.end(); it++)
    {
        if (symTable.count(it->first))
        {
            s = symTable[it->first];
            pos = it->second;
            output[pos++] = s[2];
            output[pos++] = s[3];
            output[pos++] = s[0];
            output[pos++] = s[1];
        }
        else
        {
            std::cerr << "Error : label \"" << it->first << "\" was not found.\n";
            return ERROR;
        }
    }
    return OKAY;
}
