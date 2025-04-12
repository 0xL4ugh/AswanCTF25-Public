#include <iostream>
#include <fstream>
#include <cstdlib>

int main() {
    std::string input;
    std::getline(std::cin, input); 

    int val = std::strtol(input.c_str(), nullptr, 0);

    if (val == 1337) {
        std::ifstream flag("flag.txt");
        std::string line;
        std::getline(flag, line);
        std::cout << "You are worthy of my flag: " << line << std::endl;
    } else {
        std::cout << "Nope, I don't like you" << std::endl;
    }

    return 0;
}
