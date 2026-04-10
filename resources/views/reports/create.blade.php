@extends('drs.layout.template')

@section('main')
    <main id="mainContent" class="main-content" style="display: block">
        <div class="page-header">
            <button class="back-button" onclick="app.showPage('home')">
                <span class="material-icons">arrow_back</span>
                Back to Home
            </button>
            <div class="page-title">Create Report</div>
            <div class="placeholder"></div>
        </div>

        <form id="createReportForm" onsubmit="app.handleCreateReport(event)">
            <!-- Match Information -->
            <div class="form-section">
                <div class="form-section-title">Match Information</div>

                <div class="input-group">
                    <label class="label">Match Number *</label>
                    <input class="input" name="matchNumber" placeholder="Enter match number" required>
                </div>

                <div class="row">
                    <div class="half-width">
                        <label class="label">Date</label>
                        <input class="input" type="date" name="date"
                            value="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div class="half-width">
                        <label class="label">Time (24-hour format: HH:MM)</label>
                        <input class="input" type="text" name="time" placeholder="14:30"
                            pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$"
                            value="${new Date().toTimeString().split(' ')[0].slice(0, 5)}" maxlength="5" required>
                    </div>
                </div>

                <div class="row">
                    <div class="half-width">
                        <label class="label">Expected Gates Opening Time</label>
                        <input class="input" type="text" name="expectedGatesTime" placeholder="12:30"
                            pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" maxlength="5">
                    </div>
                    <div class="half-width">
                        <label class="label">Actual Gates Opening Time</label>
                        <input class="input" type="text" name="actualGatesTime" placeholder="12:35"
                            pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9]$" maxlength="5">
                    </div>
                </div>

                <div class="row">
                    <div class="half-width">
                        <label class="label">Tournament</label>
                        <select class="input" name="tournament" id="tournamentSelect"
                            onchange="app.handleTournamentChange()">
                            <option value="FIFA Arab Cup Qatar 2025">FIFA Arab Cup Qatar 2025</option>
                            <option value="Gulf Cup U-17">Gulf Cup U-17</option>
                            <option value="Gulf Cup U-23">Gulf Cup U-23</option>
                            <option value="FIFA Intercontinental Cup 2025">FIFA Intercontinental Cup 2025</option>
                            <option value="FIFA U-17 World Cup">FIFA U-17 World Cup</option>
                            <option value="Other">Other (specify below)</option>
                        </select>
                        <input class="input" name="tournamentCustom" id="tournamentCustom"
                            placeholder="Enter custom tournament name" style="display: none; margin-top: 10px;">
                    </div>
                    <div class="half-width">
                        <label class="label">Stage</label>
                        <input class="input" name="stage" placeholder="Enter stage">
                    </div>
                </div>

                <div class="input-group">
                    <label class="label">Stadium</label>
                    <input class="input" name="stadium" placeholder="Enter stadium name">
                </div>

                <div class="row">
                    <div class="half-width">
                        <label class="label">Team A</label>
                        <input class="input" name="homeTeam" placeholder="Team name">
                    </div>
                    <div class="half-width">
                        <label class="label">Team B</label>
                        <input class="input" name="awayTeam" placeholder="Team name">
                    </div>
                </div>

                <div class="input-group">
                    <label class="label">Final Score *</label>
                    <input class="input" name="finalScore" placeholder="0:0" required>
                </div>

                <div class="input-group">
                    <label class="label">Official Attendance</label>
                    <input class="input" type="number" name="attendance" placeholder="Official attendance"
                        value="0" min="0">
                </div>

                <div class="input-group">
                    <label class="label">Venue Manager Name *</label>
                    <input class="input" name="venueManagerName" placeholder="Enter venue manager full name" required>
                </div>
            </div>

            <!-- Actions Taken by VUM -->
            <div class="form-section">
                <div class="form-section-title">Actions Taken by VUM (Pre-Match Day)</div>
                <div style="font-size: 12px; color: #666; margin-bottom: 8px; font-style: italic;">💡 Tip: Use bullet
                    points
                    (•) or line breaks for better formatting. Your formatting will be preserved.</div>
                <textarea class="textarea" name="actionsVUM"
                    placeholder="Describe actions taken by VUM...\n\nExample with bullets:\n• Action item 1\n• Action item 2\n• Action item 3\n\nOr use numbered list:\n1. First action\n2. Second action\n3. Third action"
                    style="white-space: pre-wrap; font-family: inherit;"></textarea>
            </div>

            <!-- Client Groups -->
            <div class="form-section">
                <div class="form-section-title">Client Groups</div>

                <!-- Spectators -->
                <div class="sub-section">
                    <div class="sub-section-title"><strong>Spectators</strong></div>
                    <div class="row">
                        <div class="third-width">
                            <label class="label">VVIP</label>
                            <input class="input" type="number" name="vvip" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">VIP</label>
                            <input class="input" type="number" name="vip" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">Hospitality (skyboxes)</label>
                            <input class="input" type="number" name="hospitalitySkyboxes" value="0"
                                min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="half-width">
                            <label class="label">Hospitality (lounges)</label>
                            <input class="input" type="number" name="hospitalityLounges" value="0"
                                min="0">
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="sub-section">
                    <div class="sub-section-title"><strong>Media</strong></div>
                    <div class="row">
                        <div class="third-width">
                            <label class="label">Media tribune</label>
                            <input class="input" type="number" name="mediaTribune" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">Photo tribune</label>
                            <input class="input" type="number" name="photoTribune" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">Photo pitch</label>
                            <input class="input" type="number" name="photoPitch" value="0" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="half-width">
                            <label class="label">Mixed zone</label>
                            <input class="input" type="number" name="mixedZone" value="0" min="0">
                        </div>
                        <div class="half-width">
                            <label class="label">Press-conference</label>
                            <input class="input" type="number" name="pressConference" value="0" min="0">
                        </div>
                    </div>
                </div>

                <!-- Broadcast -->
                <div class="sub-section">
                    <div class="sub-section-title"><strong>Broadcast</strong></div>
                    <div class="input-group">
                        <label class="label">Personnel</label>
                        <input class="input" type="number" name="broadcastPersonnel" value="0" min="0">
                    </div>
                </div>

                <!-- Services -->
                <div class="sub-section">
                    <div class="sub-section-title"><strong>Services</strong></div>
                    <div class="row">
                        <div class="third-width">
                            <label class="label">Volunteers (total)</label>
                            <input class="input" type="number" name="volunteersTotal" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">SPS (Outer perimeter)</label>
                            <input class="input" type="number" name="spsOuter" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">SPS (Inner perimeter)</label>
                            <input class="input" type="number" name="spsInner" value="0" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="third-width">
                            <label class="label">F&B Concessions</label>
                            <input class="input" type="number" name="fnbConcessions" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">Medical Staff</label>
                            <input class="input" type="number" name="medicalStaff" value="0" min="0">
                        </div>
                        <div class="third-width">
                            <label class="label">Cleaning & Waste</label>
                            <input class="input" type="number" name="cleaningWaste" value="0" min="0">
                        </div>
                    </div>
                    <div class="input-group">
                        <label class="label">Hospitality</label>
                        <input class="input" type="number" name="hospitalityServices" value="0" min="0">
                    </div>
                </div>
            </div>

            <!-- PSA/Turnstiles operations -->
            <div class="form-section">
                <div class="form-section-title"><strong>PSA/Turnstiles operations</strong></div>

                <div class="row">
                    <div class="third-width">
                        <label class="label">PSA Scanned</label>
                        <input class="input" type="number" name="psaScanned" value="0" min="0">
                    </div>
                    <div class="third-width">
                        <label class="label">Turnstiles Scanned</label>
                        <input class="input" type="number" name="turnstilesScanned" value="0" min="0">
                    </div>
                    <div class="third-width">
                        <label class="label">Accreditation Scanned</label>
                        <input class="input" type="number" name="accreditationScanned" value="0" min="0">
                    </div>
                </div>
            </div>

            <!-- Mobility Section -->
            <div class="form-section">
                <div class="form-section-title">Mobility Section</div>
                <div style="font-size: 12px; color: #666; margin-bottom: 8px; font-style: italic;">💡 Tip: Use bullet
                    points
                    (•) or dashes (-) for better formatting. Your formatting will be preserved.</div>
                <textarea class="textarea" name="transportSection"
                    placeholder="Describe mobility arrangements...\n\nExample with bullets:\n• Bus route 1 - North entrance\n• Bus route 2 - South entrance\n• Metro station access\n\nOr use dashes:\n- Parking lot A: 500 spaces\n- Parking lot B: 300 spaces"
                    style="white-space: pre-wrap; font-family: inherit;"></textarea>
            </div>

            <!-- Shukran Programme -->
            <div class="form-section">
                <div class="form-section-title">Shukran Programme</div>
                <div class="compliance-row">
                    <label class="label">Was Shukran Programme implemented?</label>
                    <label class="switch">
                        <input type="checkbox" id="shukranProgramme">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <!-- General Issues -->
            <div class="form-section">
                <div class="form-section-title">Venue Manager General Comments</div>
                <div style="font-size: 12px; color: #666; margin-bottom: 8px; font-style: italic;">💡 Tip: Use bullet
                    points
                    (•) or dashes (-) for better formatting. Your formatting will be preserved.</div>
                <textarea class="textarea" name="generalIssues"
                    placeholder="Describe general comments...\n\nExample with bullets:\n• Everything went smoothly\n• Minor delay at gate 3\n• Excellent crowd management\n\nOr use dashes:\n- Weather was perfect\n- No major incidents\n- Staff performed well"
                    style="white-space: pre-wrap; font-family: inherit;"></textarea>
            </div>

            <!-- VOC Issue Log Inputs -->
            <div class="form-section">
                <div class="form-section-title">VOC Issue Log Inputs</div>
                <div class="voc-upload-options">
                    <div class="upload-option">
                        <strong>Option 1: Upload Excel File</strong>
                        <div class="file-upload-container">
                            <input type="file" id="excelFileInput" accept=".xlsx,.xls" style="display: none;">
                            <button type="button" class="excel-upload-btn"
                                onclick="document.getElementById('excelFileInput').click()">
                                📁 Upload Excel File
                            </button>
                            <div id="excelFileName" class="file-name"></div>
                            <button type="button" id="removeExcelBtn" class="excel-remove-btn"
                                onclick="app.removeExcelFile()"
                                style="display: none; margin-left: 10px; padding: 5px 10px; background: #ff4444; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove
                                File</button>
                            <div id="excelPreview" class="excel-preview"></div>
                        </div>
                    </div>
                    <div class="upload-option">
                        <strong>Option 2: Paste Excel Data</strong>
                        <div class="voc-instructions">
                            <p>Copy and paste Excel table data below. Each row should be on a new line, with columns
                                separated
                                by tabs or spaces.</p>
                            <pre style="font-size: 10px; background: #e9ecef; padding: 8px; border-radius: 4px; margin: 5px 0;">Issue ID    Category     Description                Status    Assigned To
                            001         Safety       Emergency exit blocked     Open      Security Team
                            002         Cleaning     Spilled drink in section A Closed    Cleaning Staff</pre>
                        </div>
                        <textarea class="textarea large-textarea" name="vocIssueLog"
                            placeholder="Paste Excel table data here...\n\nExample:\nIssue ID\tCategory\tDescription\tStatus\tAssigned To\n001\tSafety\tEmergency exit blocked\tOpen\tSecurity Team\n002\tCleaning\tSpilled drink in section A\tClosed\tCleaning Staff\n\nOr with spaces:\nIssue ID    Category     Description                Status    Assigned To\n001         Safety       Emergency exit blocked     Open      Security Team\n002         Cleaning     Spilled drink in section A Closed    Cleaning Staff"></textarea>
                    </div>
                </div>
            </div>

            <!-- FA Observations / Lessons Learned -->
            <div class="form-section">
                <div class="form-section-title">FA Observations / Lessons Learned</div>
                <textarea class="textarea" name="faObservations" placeholder="Describe FA observations and lessons learned..."></textarea>
            </div>

            <!-- Photos -->
            <div class="photo-section">
                <div class="form-section-title">Photos (${this.currentPhotos.length}/10)</div>
                <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                    <input type="file" id="photoInput" multiple accept="image/*" style="display: none;">
                    <div class="photo-upload-text">📷 Add Photos</div>
                    <div class="photo-upload-subtext">Click to select images</div>
                </div>
                <div class="photo-preview" id="photoPreview"></div>
                <div class="photo-upload-text">Maximum 10 photos allowed. Photos will be included in the PDF report.</div>
            </div>

            <!-- Action Buttons -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
                <button type="button" class="save-draft-button" onclick="app.saveDraftManually()"
                    style="padding: 15px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span class="button-icon">💾</span>
                    Save as Draft
                </button>
                <button type="submit" class="submit-button" style="margin: 0;">
                    <span class="button-icon">✅</span>
                    Create Report (Ctrl+S)
                </button>
            </div>
        </form>

        <!-- Draft save indicator -->
        <div id="autoSaveIndicator"
            style="position: fixed; bottom: 70px; right: 20px; background: #4CAF50; color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; display: none; z-index: 1000;">
            ✓ Draft saved successfully
        </div>

        <!-- Page state indicator -->
        <div id="pageStateIndicator"
            style="position: fixed; bottom: 20px; right: 20px; background: #2196F3; color: white; padding: 6px 12px; border-radius: 15px; font-size: 11px; display: none; z-index: 1000;">
            📍 Position saved
        </div>
    </main>
@endsection
