const styles: Record<string, string>={
    "Styl pierwszy": "style-1.css",
    "Styl drugi": "style-2.css",
    "Styl trzeci": "style-3.css"
};

function applyStyle(fileName: string): void{
    const oldLink = document.getElementById('dynamic-style');
    if(oldLink){
        oldLink.remove();
    }

    const link=document.createElement('link');
    link.id='dynamic-style';
    link.rel='stylesheet';
    link.href=fileName;
    document.head.appendChild(link);
}

function createMenu(): void{
    const nav = document.createElement('nav');
    nav.style.padding = '20px';
    nav.style.display = 'flex';
    nav.style.gap = '15px';

    for(const [name, file] of Object.entries(styles)){
        const button = document.createElement('button');
        button.innerText=name;

        button.style.backgroundColor= 'transparent';
        button.style.color = 'white';
        button.style.fontWeight = 'bold';
        button.style.border = '2px solid grey';
        button.style.padding = '12px 24px';
        button.style.fontSize = '18px';
        button.style.cursor = 'pointer';
        button.style.borderRadius = '8px';
        (button.style as any).webkitTextStroke = '1px grey';

        button.onclick = () => applyStyle(file);

        nav.appendChild(button);
    }
    document.body.prepend(nav);
}

createMenu()

applyStyle(Object.values(styles)[0]);
