#ifndef _assembler_h
#define _assembler_h

#include <iostream>
#include <fstream>
#include <vector>
#include <string>
#include <map>


enum Signal
{
    OKAY,
    ERROR,
    END
};
// enum is used to define custom data type eg. the enum Signal can only take OKAY,ERROR,END as their value..!!
enum State
{
    START,
    LABEL_COMMAND_REC,
    COMMAND,
    OP1REC,
    OP1,
    OP2REC,
    OP2,
    EXITREC,
    UNEXPECTED,
    EXIT
};

struct CommandOpCode
{
    char op1;
    char op2;
    char code[2];
    CommandOpCode()
    {
    }
    CommandOpCode(char _op1, char _op2, std::string _code) : op1(_op1), op2(_op2)
    {
        code[0] = _code[0];
        code[1] = _code[1];
    }
    void setCode(char _code[])
    {
        code[0] = _code[0];
        code[1] = _code[1];
    }
};

class Assembler
{
public:
    Assembler(std::string, bool _newline = false, int org = 2000);
    ~Assembler();
    int exec();

private:
    std::ifstream fin;
    int line;
    int address;
    const int ORG;
    std::string label, command, op1, op2;
    std::multimap<std::string, CommandOpCode> comTable;
    std::map<std::string, std::string> symTable;
    std::map<std::string, int> labels;
    std::vector<char> output;

    bool labelRead, opcode1Read, opcode2Read;
    Signal tokenize();
    std::string hexAddress(int a = -1);

    //output control flags
    bool newline;

    bool constructComTable();
    Signal pass1();
    Signal pass2();
};

#endif


