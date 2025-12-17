
# setup_webdisk2.ps1
# Contributor-safe WebDAV drive mapper for choose90.org

$DriveLetter = "Z:"
$WebDavPath = "\\choose90.org@SSL@2078\DavWWWRoot"
$Username = "john@choose90.org"
$PasswordPlain = "Pecandesk1!"

# Convert password to secure string
$SecurePassword = ConvertTo-SecureString $PasswordPlain -AsPlainText -Force
$Credential = New-Object System.Management.Automation.PSCredential ($Username, $SecurePassword)

# Check if drive is already mapped
if (Test-Path $DriveLetter) {
    Write-Host "$DriveLetter already exists. Skipping remap."
} else {
    Write-Host "Mapping $DriveLetter to $WebDavPath..."
    try {
        New-PSDrive -Name "Z" -PSProvider FileSystem -Root $WebDavPath -Credential $Credential -Persist
        Write-Host "Drive mapped successfully."
    } catch {
        Write-Host "Failed to map drive: $($_.Exception.Message)"
    }
}