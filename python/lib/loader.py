#!/usr/bin/python

import os
import assembler

#Function to load the final assembly code recieved by linking all the files
def load( fileNames ):
	i = 0
	asCode = []
	i=400
	for fileName in fileNames :
		fileName = fileName.split('.')[0]
		loadFile[fileName] = i#input('Where To Load ' + fileName + '.s : ') Shubham shukla edited this becaus I am giving it the correct order
		i=i+1000
	i=0
# Generates file which run on Simulator
	for fileName in fileNames :
		fileName = fileName.split('.')[0]
		inputFile = open(fileName+'.l.8085', 'r')
		code =  inputFile.read()
		lines = code.split('\n')
		for line in lines :
			line = line.lstrip().rstrip()
			if(line!=''):
				tags = line.split(' ')
				for tag in tags : 
					if '$' in tag :
						val = int(tag.split('$')[1])+loadFile[fileNames[0].split('.')[0]]+fileLength[fileName]
						line = line.replace(tag,str(val))
					if '#' in tag :
						add = tag.split('#')[1].split('+')[-1]
						if add.isdigit() :
							add = int(add)
						else :
							add = 0
						lnFile = tag.split('#')[0]
						val = loadFile[fileNames[0].split('.')[0]]+fileLength[lnFile] + variableTable[lnFile][tag.split('#')[1].split('+')[0]] + add 
						line = line.replace(tag,str(val))
				asCode.append(line.lstrip().rstrip())
		inputFile.close()
	code =  '\n'.join(asCode)
	outputFile = open(fileNames[0].split('.')[0]+'.s.8085', 'w')
	outputFile.write(code)
	outputFile.close()

# Generates file which represent virtual memory
	asCode = []
	for fileName in fileNames :
		fileName = fileName.split('.')[0]
		inputFile = open(fileName+'.l.8085', 'r')
		code =  inputFile.read()
		while i != loadFile[fileName] : 
			i = i+1
			asCode.append('')
		lines = code.split('\n')
		for line in lines :
			line = line.lstrip().rstrip()
			if(line!=''):
				tags = line.split(' ')
				for tag in tags : 
					if '$' in tag :
						val = int(tag.split('$')[1])+loadFile[fileName]
						line = line.replace(tag,str(val))
					if '#' in tag :
						add = tag.split('#')[1].split('+')[-1]
						if add.isdigit() :
							add = int(add)
						else :
							add = 0
						lnFile = tag.split('#')[0]
						val = loadFile[lnFile] + variableTable[lnFile][tag.split('#')[1].split('+')[0]] + add 
						line = line.replace(tag,str(val))
				asCode.append(line.lstrip().rstrip())
			i = i+1
		inputFile.close()
	code =  '\n'.join(asCode)
	outputFile = open(fileNames[0].split('.')[0]+'.8085', 'w')
	outputFile.write(code)
	outputFile.close()

loadFile = {}
variableTable = assembler.variableTable
fileLength = assembler.fileLength