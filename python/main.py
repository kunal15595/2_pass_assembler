#!/usr/bin/python

from lib import pre_processor,assembler,linker,loader
import sys
fileNames = []
fileNames=sys.argv
fileNames.remove(fileNames[0])
# print fileNames
# inFile = raw_input('Enter the file name : ');
# i=0;
# while (inFile!='') :
# 	fileNames.append(inFile)
# 	inFile = raw_input('Enter the file name : ');
# 	i=i+1

pre_processor.replace_macros ( fileNames )
pre_processor.replace_opcodes ( fileNames )
# raw_input('Pre-Processing Done ......\nPress Enter to Coninue..... ')
assembler.createSymbolTable( fileNames )
# raw_input('Pass 1 Assembling Done ......\nPress Enter to Coninue..... ')
assembler.replaceTable( fileNames )
# raw_input('Pass 2 Assembling Done ......\nPress Enter to Coninue..... ')
linker.link(fileNames)
# raw_input('Linking Done ......\nPress Enter to Coninue..... ')
loader.load(fileNames)
# raw_input('Loading Done ......\nPress Enter to Coninue..... ')