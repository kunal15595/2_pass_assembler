#!/usr/bin/python

import macroReplacer
import opcodeReplacer

def replace_macros( fileNames ):
	macroReplacer.createMacroTable ( fileNames )
	macroReplacer.replaceMacros ( fileNames )

def replace_opcodes( fileNames ):
	opcodeReplacer.createOpcodeTable ()
	opcodeReplacer.replaceOpcodes ( fileNames )
