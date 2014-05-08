;Sample program for demonstrating 8085 assembler

START: CMA
CALL END
MVI A, 56H
MOV B, A
MVI A, 32H
ADD B
MOV C, A
ANI 99H
END: CALL START	
HLT
