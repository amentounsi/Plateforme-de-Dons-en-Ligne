function verif(e) {
    
    const nom = document.getElementById('nom').value.trim();
    const prenom = document.getElementById('prenom')?.value.trim() || '';
    const pseudo = document.getElementById('pseudo')?.value.trim() || '';
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const cin = document.getElementById('cin').value.trim();
    const role = document.querySelector('input[name="role"]:checked')?.value;
    const nomAssociation = document.getElementById('nom_association').value.trim();
    const adresseAssociation = document.getElementById('adresse_association')?.value.trim() || '';
    const identifiantFiscal = document.getElementById('identifiant_fiscal').value.trim();
    const logo = document.getElementById('logo').files.length;

    let valid = true;
    let messages = [];

    
    const emailRegex = /^[\w.-]+@([\w-]+\.)+[\w-]{2,4}$/;
    const cinRegex = /^[0-9]{8}$/;
    const pseudoRegex = /^[A-Za-z]+$/;
    const motdepasseRegex = /^[A-Za-z0-9]{8,}[$#]$/;
    const identifiantFiscalRegex = /^\$[A-Z]{3}[0-9]{2}$/;

    if (nom.length < 2) {
        valid = false;
        messages.push("Le nom est requis.");
    }

    if (prenom.length < 2) {
        valid = false;
        messages.push("Le prénom est requis.");
    }

    if (!pseudoRegex.test(pseudo)) {
        valid = false;
        messages.push("Le pseudo doit contenir uniquement des lettres.");
    }

    if (!emailRegex.test(email)) {
        valid = false;
        messages.push("L'adresse email est invalide.");
    }

    if (!cinRegex.test(cin)) {
        valid = false;
        messages.push("Le CIN doit contenir exactement 8 chiffres.");
    }

    if (!motdepasseRegex.test(password)) {
        valid = false;
        messages.push("Le mot de passe doit contenir au moins 8 caractères et se terminer par $ ou #.");
    }

    if (role === 'association') {
        if (nomAssociation.length < 2) {
            valid = false;
            messages.push("Le nom de l'association est requis.");
        }
        if (adresseAssociation.length < 5) {
            valid = false;
            messages.push("L'adresse de l'association est requise.");
        }
        if (!identifiantFiscalRegex.test(identifiantFiscal)) {
            valid = false;
            messages.push("L'identifiant fiscal est invalide. Format attendu : \$ABC12.");
        }
        if (!logo) {
            valid = false;
            messages.push("Le logo de l'association est obligatoire.");
        }
    }

    // ✅ Si erreur ➔ empêcher l'envoi du formulaire
    if (!valid) {
        if (e) e.preventDefault(); // Bloquer l'envoi
        alert(messages.join("\n"));
        return false;
    }
    return true;
}

function setupRoleSwitching() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const associationFields = document.getElementById('associationFields');
  
    roleInputs.forEach(input => {
      input.addEventListener('change', function () {
        if (this.value === 'association') {
          associationFields.style.display = 'block';
          document.getElementById('nom_association').required = true;
          document.getElementById('identifiant_fiscal').required = true;
        } else {
          associationFields.style.display = 'none';
          document.getElementById('nom_association').required = false;
          document.getElementById('identifiant_fiscal').required = false;
        }
      });
    });
  }
  
  // On lance la fonction quand la page est complètement chargée
  document.addEventListener('DOMContentLoaded', function() {
    setupRoleSwitching();
  });
  
