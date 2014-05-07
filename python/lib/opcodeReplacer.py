#!/usr/bin/python

#Function to create a createOpcodeTable for each file
def createOpcodeTable():
	inputFile = open('python/config/opcodes.config', 'r')
	code = inputFile.read()
	lines = code.split('\n')
	lastOpcode =''
	expCode = []
	flag = 0
	for line in lines :
		line = line.lstrip().rstrip()
		if 'OPCODE' in line:
			lastOpcode  = line.split(' ')[1]
			opcodeTable[lastOpcode ] = ' '.join(line.split(' ')[2:])
			flag = 1
		elif 'OPEND' in line : 
			opcodeCode[lastOpcode ]='\n'.join(expCode)
			flag = 0
			expCode = []
			lastOpcode  =''
		elif flag == 1 :
			expCode.append(line)

#Function to expand each occurance of opcodes in the code via looking into the opCodeTable
def replaceOpcodes( fileNames ):
	for fileName in fileNames :
		replacements = True
		while replacements :
			replacements = False
			inputFile = open(fileName, 'r')
			code =  inputFile.read()
			lines = code.split('\n')
			asCode =[]
			for line in lines :
				line = line.lstrip().rstrip()
				tag = opcodePresent(line)
				if  tag != '':
					pams =  ''.join(line.split(tag)[1:]).lstrip().rstrip().split(',')
					tag_pam = mapping(tag,pams)
					line = opcodeCode[tag]
					for pam in tag_pam :
						line = line.replace(pam,tag_pam[pam])
					replacements = True
				line = variablePresent(line)
				if(line!='') :
					asCode.append(line);
			code =  '\n'.join(asCode)
			outputFile = open(fileName, 'wb')
			outputFile.write(code)
			code = code.replace(' DS',': DS')
			code = code.replace(' DB',': DB')
			code = code.replace('EXTERN','EXTERN:')
			displayFile = open(fileName+'.s', 'wb')
			displayFile.write(code)
			outputFile.close()
			displayFile.close()
			inputFile.close()

# Function to map parameters of each of custom opcode with the corrosponding parameters provided in the code
def mapping( tag,pams ):
	tag_pam ={}
	pam_list = opcodeTable[tag].lstrip().rstrip().split(',')
	i=0
	for pam in pam_list:
		tag_pam[str(pam)] = pams[i]
		i=i+1
	return tag_pam	

# Function to check if the custom opcode is present in the code
def opcodePresent( line ):
	tags = line.split(' ')
	for tag in tags :
		if tag in opcodeTable :
			return tag
	return ''

def variablePresent( line ):
	tags = line.split(' ')
	for tag in tags :
		if '[' in tag :
			add = tag.split('[')[-1].split(']')[0]
			if not add.isdigit() :
				add = '0'
			#line = line.replace(tag,tag.split('[')[0].strip()+'+'+str(add))
			line = line.replace('['+add+']','+'+str(add))
	return line

opcodeTable = {}
opcodeCode = {}
