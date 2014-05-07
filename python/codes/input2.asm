LXI H, 6200H ; Initialize lookup table pointer
LXI D, 6100H ; Initialize source memory pointer
LXI B, 7000H ; Initialize destination memory pointer
BACK: LDAX D ; Get the number
MOV L, A ; A point to the square
MOV A, M ; Get the square
STAX B ; Store the result at destination memory location
INX D ; Increment source memory pointer
INX B ; Increment destination memory pointer
MOV A, C
CPI 05H ; Check for last number
JNZ BACK ; If not repeat
HLT ; Terminate program execution