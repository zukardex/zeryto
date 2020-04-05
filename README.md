# Zeryto
Zeryto is a simple cryptosystem moulded out in PHP with the intention of making the data transfer between the server and the client secure.
Zeryto allows anyone to encrypt the text using a key and there are two keys available to decrypt the text.
Encryption is done through 3 processes here ie, Alpha encoding, Salting, and Beta encoding.
The secondary key for decryption is the "z value". Decryption takes less time and low CPU power if this "z value" is used for decryption.
You can add new characters to the $alph array if you want more characters to be supported.
